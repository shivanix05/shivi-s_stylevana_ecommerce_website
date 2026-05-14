# ============================================================
# Shivis Stylevana - Database Config
# Sirf yahan apna DB password/user change karo
# ============================================================

# DB_CONFIG = {
#     'host':     'localhost',
#     'user':     'root',          # <-- apna MySQL username
#     'password': 'root123',              # <-- apna MySQL password
#     'database': 'shivi-stylevana',
    
# }
DB_CONFIG = {
    'host': '127.0.0.1', # 'localhost' ki jagah ye likh kar dekhein
    'user': 'root',
    'password': 'root123', # Agar password nahi hai toh khali chhodein
    'database': 'shivi-stylevana', # Check karein database ka name yahi hai na?
    'raise_on_warnings': True
}