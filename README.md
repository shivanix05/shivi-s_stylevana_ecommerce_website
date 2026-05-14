# shivi-s_stylevana_ecommerce_website
this is my shivi's stylewana an ecommerce website which i made of HTML,CSS,javascript and php and the database connected to my sql workbench
want to uplaod all th files

This repository contains the full website project with user pages, admin pages, product management, cart and checkout flow, plus the AI recommendation integration.

# Shivis Stylevana — AI Recommendation System
## Complete Setup Guide (Hindi)

---

## 📁 Files List

```
stylevana_ai/
├── app.py                    ← Flask API server (yahi chalao)
├── recommender.py            ← AI engine (scoring logic)
├── db_config.py              ← Database config
├── recommendation_widget.js  ← PHP site mein lagane wala script
├── admin_stats.html          ← Admin dashboard
└── requirements.txt          ← Python libraries
```

---

## ⚙️ STEP 1: Python Setup

```bash
# Python install karo (agar nahi hai)
# https://python.org se download karo

# Libraries install karo
pip install -r requirements.txt
```

---

## ⚙️ STEP 2: Database Config

`db_config.py` kholao aur apna password daalo:

```python
DB_CONFIG = {
    'host':     'localhost',
    'user':     'root',        # apna MySQL user
    'password': 'YOUR_PASS',   # apna MySQL password
    'database': 'shivi-stylevana',
}
```

---

## ⚙️ STEP 3: Server Chalao

```bash
cd stylevana_ai
python app.py
```

Server chalega: `http://localhost:5000`

Test karo browser mein:
```
http://localhost:5000/health
```
Agar dikhe: `{"status": "ok", "db": "connected"}` — sab theek hai!

---

## ⚙️ STEP 4: PHP Site Mein Lagao

### A) Product Page pe Recommendations (Personalized)

Apne `product_detail.php` mein yeh add karo:

```php
<!-- Jahan recommendations dikhani ho wahan yeh div daalo -->
<div id="sv-recommendations"></div>

<!-- Footer se pehle yeh script lagao -->
<script src="recommendation_widget.js"></script>
<script>
StylevanaRec.init({
    userEmail:  '<?= isset($_SESSION["user_email"]) ? $_SESSION["user_email"] : "" ?>',
    currentPid: '<?= $product_id ?>',
    title:      'Aapke liye Recommendations',
    subtitle:   'Aapki pasand ke hisaab se',
    limit:      8
});
</script>
```

### B) Same Product Jaisi Cheezein (Similar Products)

```php
<div id="sv-similar"></div>
<script src="recommendation_widget.js"></script>
<script>
StylevanaRec.initSimilar({
    currentPid: '<?= $product_id ?>',
    title:      'Aisi aur cheezein',
    limit:      6
});
</script>
```

### C) Homepage pe (Guest/New Users ke liye Popular)

```php
<div id="sv-recommendations"></div>
<script src="recommendation_widget.js"></script>
<script>
StylevanaRec.init({
    userEmail: '<?= isset($_SESSION["user_email"]) ? $_SESSION["user_email"] : "" ?>',
    title:     'Trending Products',
    subtitle:  'Sabse popular cheezein',
    limit:     8
});
</script>
```

---

## ⚙️ STEP 5: Admin Dashboard

`admin_stats.html` browser mein kholao ya apne admin panel mein include karo.

Admin token change karo (security ke liye):
- `app.py` mein: `ADMIN_TOKEN = 'apna_secret_token'`
- `admin_stats.html` mein: `const ADMIN_TOKEN = 'apna_secret_token'`

---

## 🧠 Scoring System Kaise Kaam Karta Hai

| Action | Score |
|--------|-------|
| Cart mein daala | +10 per qty |
| Order kiya | +25 per qty |
| Wishlist mein daala | +8 |
| Review diya | +5 |
| 4+ star review | +3 bonus |
| Favorite category | +15/+12/+9 |
| Featured product | +5 |
| Discount wala | up to +10 |

Jis product ka score sabse zyada → woh sabse pehle dikha!

---

## 🌐 API Endpoints

| URL | Kaam |
|-----|------|
| `/recommend?email=X&pid=Y&limit=8` | Personalized recommendations |
| `/similar?pid=Y&limit=6` | Similar products |
| `/popular?limit=10` | Global popular (new users) |
| `/admin/stats?token=X` | Admin stats |
| `/health` | Server check |

---

## ❓ Common Issues

**Q: Server start nahi ho raha?**
```bash
pip install flask mysql-connector-python flask-cors
```

**Q: DB connect nahi ho raha?**
- `db_config.py` mein password check karo
- MySQL chal raha hai ya nahi check karo

**Q: PHP site pe show nahi ho raha?**
- Flask server chal raha hai? (`python app.py`)
- Browser console mein error check karo (F12)
- `recommendation_widget.js` ka path sahi hai?

**Q: Production pe kaise lagaen?**
- `localhost:5000` ki jagah apna server IP/domain use karo
- `recommendation_widget.js` mein `API_BASE` update karo
- Gunicorn use karo production ke liye:
  ```bash
  pip install gunicorn
  gunicorn -w 4 app:app -b 0.0.0.0:5000
  ```
