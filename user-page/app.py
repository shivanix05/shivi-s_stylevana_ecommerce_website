"""
Shivis Stylevana - Flask AI Recommendation API Server
======================================================
Yeh server aapki PHP website ko recommendations deta hai.

INSTALL:
  pip install flask mysql-connector-python flask-cors

RUN:
  python app.py

API ENDPOINTS:
  GET  /recommend?email=user@gmail.com&pid=5&limit=8
  GET  /similar?pid=5&limit=6
  GET  /popular?limit=10
  GET  /admin/stats
  GET  /health
"""
import traceback
from flask import Flask, request, jsonify
from flask_cors import CORS
app = Flask(__name__)
CORS(app)
# from recommender import (
#     get_recommendations,
#     get_similar_products,
#     get_globally_popular,
#     get_admin_stats,
#     get_connection
# )
from recommender import get_connection
from recommender import (
    get_connection,
    get_recommendations,
    get_similar_products,
     get_globally_popular,
    get_admin_stats
)


  # PHP site se requests allow karne ke liye

@app.route('/')
def home():
    return "Shivis Stylevana AI Server Running"
# ============================================================
# Helper: Product details by PID list
# ============================================================
def fetch_products_by_pids(pids: list) -> list:
    if not pids:
        return []
    conn = get_connection()
    cursor = conn.cursor(dictionary=True)
    placeholders = ','.join(['%s'] * len(pids))
    cursor.execute(
        f"SELECT * FROM shop WHERE pid IN ({placeholders}) AND stock_qty > 0",
        pids
    )
    rows = cursor.fetchall()
    cursor.close()
    conn.close()
    # Order preserve karo
    pid_map = {str(r['pid']): r for r in rows}
    return [pid_map[str(p)] for p in pids if str(p) in pid_map]


def product_to_dict(p: dict) -> dict:
    """Product dict ko JSON-safe banao."""
    return {
        'pid':             str(p.get('pid', '')),
        'productname':     p.get('productname', ''),
        'brand_name':      p.get('brand_name', ''),
        'productphoto':    p.get('productphoto', ''),
        'productdescription': p.get('productdescription', ''),
        'category':        p.get('category', ''),
        'productprice':    float(p.get('productprice') or 0),
        'original_price':  float(p.get('original_price') or 0),
        'offer_text':      p.get('offer_text', ''),
        'delivery_type':   p.get('delivery_type', ''),
        'stock_qty':       int(p.get('stock_qty') or 0),
        'is_featured':     bool(p.get('is_featured')),
        'rec_score':       float(p.get('rec_score') or 0),
        'avg_rating':      float(p.get('avg_rating') or 0),
        'order_count':     int(p.get('order_count') or 0),
    }

    
# ============================================================
# ENDPOINT 1: /recommend  — User ke liye personalized
# ============================================================
# ============================================================
# ENDPOINT 1: /recommend
# ============================================================
@app.route('/recommend', methods=['GET'])
def recommend():

    print("RECOMMEND API HIT")

    email = request.args.get('email', '').strip()
    pid = request.args.get('pid', None)
    limit = int(request.args.get('limit', 8))

    print("EMAIL:", email)
    print("PID:", pid)
    print("LIMIT:", limit)

    if not email:
        return jsonify({'error': 'email parameter required hai'}), 400

    try:
        print("CALLING get_recommendations")

        recs = get_recommendations(
            user_email=email,
            current_pid=pid,
            limit=min(limit, 20)
        )

        print("RECOMMENDATIONS FETCHED")

        return jsonify({
            'status': 'ok',
            'email': email,
            'count': len(recs),
            'recommendations': [product_to_dict(p) for p in recs]
        })

    except Exception as e:
        print("ERROR INSIDE RECOMMEND")
        traceback.print_exc()

        return jsonify({
            'error': str(e)
        }), 500    
    
    
    """
    PHP se call karo:
    fetch('http://localhost:5000/recommend?email=user@gmail.com&pid=5&limit=8')

    Params:
      email    : User ka gmail (required)
      pid      : Current product ID exclude karne ke liye (optional)
      limit    : Kitne products chahiye (default 8)
      price_min: Minimum price filter (optional)
      price_max: Maximum price filter (optional)
    """
   

# ============================================================
# ENDPOINT 2: /similar  — Same product jaisi cheezein
# ============================================================
@app.route('/similar', methods=['GET'])
def similar():
    print("SIMILAR API HIT")
    """
    Product page pe "Aisi aur cheezein" section ke liye.

    Params:
      pid   : Product ID (required)
      limit : Kitne chahiye (default 6)
    """
    pid   = request.args.get('pid', '').strip()
    limit = int(request.args.get('limit', 6))

    if not pid:
        return jsonify({'error': 'pid parameter required hai'}), 400

    try:
        similar = get_similar_products(pid=pid, limit=min(limit, 12))
        return jsonify({
            'status': 'ok',
            'pid':    pid,
            'count':  len(similar),
            'similar': [product_to_dict(p) for p in similar]
        })
    except Exception as e:
        traceback.print_exc()
        return jsonify({'error': str(e)}), 500


# ============================================================
# ENDPOINT 3: /popular  — Naye users ya homepage ke liye
# ============================================================
@app.route('/popular', methods=['GET'])
def popular():
    print("POPULAR API HIT")
    """
    Homepage ya naye user ke liye trending products.

    Params:
      limit : Kitne chahiye (default 10)
    """
    limit = int(request.args.get('limit', 10))

    try:
        pids    = get_globally_popular(limit=min(limit, 20))
        products = fetch_products_by_pids(pids)
        return jsonify({
            'status': 'ok',
            'count':  len(products),
            'popular': [product_to_dict(p) for p in products]
        })
    except Exception as e:
        traceback.print_exc()
        return jsonify({'error': str(e)}), 500


# ============================================================
# ENDPOINT 4: /admin/stats  — Admin dashboard
# ============================================================
@app.route('/admin/stats', methods=['GET'])
def admin_stats():
    print("ADMIN STATS API HIT")
    """
    Admin panel ke liye — kaun si cheez sabse popular hai.
    Sirf admin access kare isko!
    """
    # Basic security — admin token check (apna token set karo)
    token = request.args.get('token', '')
    ADMIN_TOKEN = 'shivis_admin_2024'  # <-- CHANGE THIS!
    if token != ADMIN_TOKEN:
        return jsonify({'error': 'Unauthorized'}), 403

    try:
        stats = get_admin_stats(limit=20)

        # Serialize all rows
        result = {}
        for key, rows in stats.items():
            result[key] = [
                {k: (float(v) if hasattr(v, '__float__') and not isinstance(v, int) else v)
                 for k, v in row.items()}
                for row in rows
            ]

        return jsonify({'status': 'ok', **result})
    except Exception as e:
        traceback.print_exc()
        return jsonify({'error': str(e)}), 500


# ============================================================
# ENDPOINT 5: /health  — Server theek hai ya nahi
# ============================================================
@app.route('/health', methods=['GET'])
def health():
    print("HEALTH API HIT")
    try:
        conn = get_connection()
        conn.close()
        return jsonify({'status': 'ok', 'db': 'connected'})
    except Exception as e:
        return jsonify({'status': 'error', 'db': str(e)}), 500


# ============================================================
# RUN
# ============================================================
if __name__ == '__main__':
    print("=" * 50)
    print("  Shivis Stylevana AI Recommendation Server")
    print("  Running at: http://localhost:5000")
    print("=" * 50)
    app.run(
        host='0.0.0.0',
        port=5000,
        debug=True,
        use_reloader=False
    )
    
    