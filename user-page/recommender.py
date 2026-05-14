import pymysql
import random
from collections import defaultdict
from db_config import DB_CONFIG

# --- STABLE CONNECTION FUNCTION ---
def get_connection():
    return pymysql.connect(
        host=DB_CONFIG.get('host', 'localhost'),
        user=DB_CONFIG.get('user', 'root'),
        password=DB_CONFIG.get('password', ''),
        database=DB_CONFIG.get('database', 'shivi_db'),
        cursorclass=pymysql.cursors.DictCursor
    )

# --- MAIN RECOMMENDATION LOGIC ---
def get_recommendations(user_email, current_pid=None, limit=8, **kwargs):
    # Email ko lowercase aur clean karo (Bahut zaruri hai!)
    user_email = str(user_email).strip().lower()
    print(f"--- [FORCE CHECK] Email: {user_email} ---")
    
    scored_products = []
    try:
        conn = get_connection()
        with conn.cursor() as cursor:
            # 1. User ki wishlist se categories nikaalo
            # Maine yaha LOWER() function lagaya hai taaki Case ki galti na ho
            cursor.execute("""
                SELECT DISTINCT LOWER(category) as cat FROM shop 
                WHERE pid IN (SELECT pid FROM wishlist WHERE LOWER(user_email) = %s)
            """, (user_email,))
            
            user_categories = {row['cat'] for row in cursor.fetchall()}
            print(f"!!! DEBUG: User Categories Found -> {user_categories}")

            # 2. Sare products uthao
            cursor.execute("SELECT * FROM shop WHERE stock_qty > 0")
            all_products = cursor.fetchall()

            # 3. Logic: Agar category match hui toh priority, warna random
            matched = []
            others = []

            for p in all_products:
                p_cat = str(p['category']).lower()
                if p_cat in user_categories:
                    matched.append(p)
                else:
                    others.append(p)

            # 4. Result Taiyaar Karo
            if matched:
                print(f"HURRAY! {len(matched)} matched products mile.")
                random.shuffle(matched)
                scored_products = matched + random.sample(others, min(len(others), 5))
            else:
                print("OH NO! Koi category match nahi hui. Default dikha raha hoon.")
                scored_products = all_products
                random.shuffle(scored_products)

        conn.close()
    except Exception as e:
        print(f"ERROR: {e}")
        return []

    return scored_products[:limit]
def get_similar_products(pid, limit=6):
    try:
        conn = get_connection()
        with conn.cursor() as cursor:
            cursor.execute("SELECT category FROM shop WHERE pid = %s", (pid,))
            res = cursor.fetchone()
            if not res: return []
            
            cursor.execute("SELECT * FROM shop WHERE category = %s AND pid != %s LIMIT %s", 
                           (res['category'], pid, limit))
            data = cursor.fetchall()
            conn.close()
            return data
    except:
        return []

# --- GLOBALLY POPULAR (Fallback) ---
def get_globally_popular(limit=10, **kwargs):
    try:
        conn = get_connection()
        with conn.cursor() as cursor:
            cursor.execute("SELECT * FROM shop WHERE stock_qty > 0 ORDER BY pid DESC LIMIT %s", (limit,))
            data = cursor.fetchall()
            conn.close()
            return data
    except:
        return []

# --- ADMIN STATS (Mandatory for app.py) ---
# --- ADMIN STATS (Mandatory for app.py) ---
def get_admin_stats(limit=20):
    stats = {
        'most_ordered': [],
        'most_wishlisted': [],
        'lost_sales': [],
        'top_categories': []
    }
    try:
        conn = get_connection()
        with conn.cursor() as cursor:
            # 1. Most Ordered Products
            cursor.execute("""
                SELECT s.productname, s.category, COUNT(o.pid) as order_count 
                FROM orders o 
                JOIN shop s ON o.pid = s.pid 
                GROUP BY o.pid 
                ORDER BY order_count DESC LIMIT %s
            """, (limit,))
            stats['most_ordered'] = cursor.fetchall()

            # 2. Most Wishlisted (Agar tumhare table ka naam 'wishlist' hai)
            cursor.execute("""
                SELECT s.productname, COUNT(w.pid) as wishlist_count 
                FROM wishlist w 
                JOIN shop s ON w.pid = s.pid 
                GROUP BY w.pid 
                ORDER BY wishlist_count DESC LIMIT %s
            """, (limit,))
            stats['most_wishlisted'] = cursor.fetchall()

            # 3. Lost Sales (Cart mein hai par order nahi hua)
           # recommender.py ke andar get_admin_stats function dhundo...

           # 3. Lost Sales (Jo filhaal carts mein hain par order nahi hue)
            cursor.execute("""
                SELECT 
                    s.productname, 
                    s.category, 
                    s.productprice, 
                    COUNT(c.pid) as cart_count 
                FROM cart c 
                JOIN shop s ON c.pid = s.pid 
                LEFT JOIN orders o ON (c.pid = o.pid AND c.user_email = o.user_email)
                WHERE o.pid IS NULL
                GROUP BY s.pid 
                ORDER BY cart_count DESC 
                LIMIT %s
            """, (limit,))
            stats['lost_sales'] = cursor.fetchall()
            # Debugging: Agar abhi bhi empty aaye toh terminal mein print hoga
            print(f"DEBUG: Lost Sales count found: {len(stats['lost_sales'])}")
            # 4. Top Categories by Revenue
            cursor.execute("""
                SELECT s.category, SUM(s.productprice) as total_revenue 
                FROM orders o 
                JOIN shop s ON o.pid = s.pid 
                GROUP BY s.category 
                ORDER BY total_revenue DESC LIMIT 5
            """)
            stats['top_categories'] = cursor.fetchall()

        conn.close()
    except Exception as e:
        print(f"!!! Error in get_admin_stats: {e}")
    
    return stats
# def get_admin_stats(limit=20):
#     return {'most_ordered': [], 'most_wishlisted': [], 'lost_sales': [], 'top_categories': []}

# --- CATEGORIES (Mandatory for app.py) ---
def get_user_favorite_categories(user_email, top_n=3):
    return []