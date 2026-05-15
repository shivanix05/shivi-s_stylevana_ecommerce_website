# Shivi's Stylevana: A Hybrid E-commerce Platform with AI Recommendations

## 🌟 Project Overview

**Shivi's Stylevana** is a modern, feature-rich e-commerce website designed to offer a seamless shopping experience for fashion, skincare, makeup, and jewellery. This project stands out with its hybrid architecture, combining the robustness of **PHP** for the main web application and **Python/Flask** for advanced AI-driven functionalities, all backed by a **MySQL** database.

The platform caters to both customers and administrators, providing a complete ecosystem for online retail. From personalized product recommendations and a real-time AI chatbot to comprehensive product and order management, Shivi's Stylevana is built to deliver a stunning and efficient e-commerce solution.

## ✨ Key Features

### User-Facing Features:

1.  **Intuitive User Interface:** Clean, responsive design for a smooth browsing experience across devices.
2.  **Secure Login & Signup:**
    *   **OTP Verification:** Enhanced security with One-Time Password (OTP) verification via email for both login and registration.
    *   **Profile Photo Upload:** Users can upload and crop their profile pictures during registration or profile editing.
3.  **Dynamic Product Catalog:**
    *   **Category Browsing:** Explore products by categories like Jewellery, Makeup, Skincare, and Fashion.
    *   **Search Functionality:** Powerful search with live suggestions for products, brands, and categories.
    *   **Product Details:** Comprehensive product pages with multiple images (up to 5), detailed descriptions, pricing (with discounts), stock status, and delivery information.
4.  **Personalized Shopping Experience:**
    *   **AI Product Recommendations:** Powered by a Python/Flask backend, offering "Picked Just For You" and "Customers Also Bought These" suggestions based on user activity and product similarity.
    *   **Wishlist:** Users can add favorite products to their wishlist for later purchase.
5.  **Seamless Purchase Flow:**
    *   **Shopping Cart:** Add multiple items to the cart, adjust quantities, and manage selected items for checkout.
    *   **"Buy Now" Option:** Direct purchase of a single product from its detail page.
    *   **Flexible Checkout:**
        *   **Cash on Delivery (COD):** Traditional payment method.
        *   **Online Payment (Razorpay Integration):** Secure and instant online payments via UPI/Card.
6.  **Order Management:**
    *   **Order History:** View all past orders with detailed summaries.
    *   **Order Tracking:** Visual status line (Placed, Shipped, Delivered/Cancelled) for each order.
    *   **Product Reviews:** Users can submit ratings, comments, and even upload photos for purchased products.
7.  **Profile Management:**
    *   **Edit Profile:** Update personal details like name, mobile, address, city, and email.
    *   **Profile Photo Cropping:** Integrated Croppie.js for easy profile picture adjustments.
8.  **Customer Support:**
    *   **Contact Us Form:** Submit queries directly to the admin, with a history of past conversations and admin replies.
    *   **AI Chatbot:** An integrated Flask-based AI chatbot (powered by Gemini API) for instant assistance and queries.

### Admin-Facing Features:

1.  **Secure Admin Login:** Dedicated login for administrators.
2.  **Comprehensive Dashboard:** Overview of key metrics like total orders, users, store rating, and recent shipments.
3.  **Product Management:**
    *   **Add Product:** Add new products with multiple photos (up to 5), detailed information, pricing, stock, offers, and featured status.
    *   **Edit Product:** Modify existing product details and images.
    *   **Delete Product:** Remove products from the inventory.
    *   **Inventory View:** Filter and search products by name, brand, category, and stock status (In Stock, Low Stock, Out of Stock).
4.  **Order Management:**
    *   **View All Orders:** List all customer orders with details.
    *   **Update Order Status:** Change order status (Pending, Processing, Shipped, Delivered, Cancelled) and add tracking IDs.
5.  **User Management:**
    *   **Customer Records:** View a list of all registered users with search functionality.
    *   **User Details:** Detailed view of individual user profiles, including contact info, address, order history, and an audit log of profile changes.
6.  **Customer Query Management:**
    *   **View Queries:** Centralized system to view all customer messages.
    *   **Reply to Queries:** Admins can respond to customer queries directly, with replies visible to the user in their contact history.
7.  **Review Management:**
    *   **View Reviews:** Browse all product reviews submitted by users.
    *   **Reply to Reviews:** Admins can add official replies to customer reviews.
    *   **Delete Reviews:** Remove inappropriate or outdated reviews.
8.  **AI Statistics & Insights (Python/Flask Backend):**
    *   **Most Ordered Products:** Identify top-selling items.
    *   **Most Wishlisted Products:** Understand customer interest.
    *   **Lost Sales Analysis:** Products frequently added to cart but not purchased.
    *   **Top Categories by Revenue:** Insights into category performance.

## 🛠️ Technology Stack

*   **Frontend:** HTML5, CSS3, JavaScript (AJAX)
*   **Backend (Main Application):** PHP (with PHPMailer for email)
*   **Backend (AI Services):** Python (Flask framework)
*   **Database:** MySQL (managed via MySQL Workbench)
*   **AI/ML:** Gemini API (for chatbot), Custom Recommendation Engine (Python)
*   **Payment Gateway:** Razorpay
*   **Libraries/Frameworks:**
    *   **PHP:** PHPMailer (for OTP emails), Composer (for dependencies)
    *   **Python:** Flask, Flask-CORS, mysql-connector-python
    *   **JavaScript:** Swiper.js (for sliders), Croppie.js (for image cropping)
    *   **CSS:** Font Awesome (icons), Google Fonts

## 🚀 Website Flow: A User's Journey

### 1. Landing Page (`index.php`)
*   **First Impression:** A visually appealing landing page showcasing the brand, featured products, and categories.
*   **Guest Experience:** Users can browse featured products and categories without logging in.
*   **Call to Action:** Prominent "Login / Sign Up" buttons to encourage engagement.

### 2. Authentication (`login.php`)
*   **Login:** Existing users enter email and password, then verify with an OTP sent to their email.
*   **Signup:** New users provide personal details (name, mobile, address, etc.), upload a profile photo (with cropping), and verify their account via OTP.
*   **Security:** OTP ensures account security and email verification.

### 3. User Dashboard (`after-login.php`)
*   **Personalized Homepage:** After successful login, users land on a personalized dashboard.
*   **Hero Banner:** Highlights new arrivals or special offers.
*   **Category Stories:** Quick access to product categories via interactive "story bubbles."
*   **AI Recommendations:** "Picked Just For You" section, dynamically generated by the Python AI backend based on user activity.
*   **Featured Products:** Handpicked items showcased prominently.
*   **Search Bar:** Global search functionality with live suggestions as the user types.
*   **Header Navigation:** Quick links to profile, wishlist, orders, and cart.

### 4. Product Browsing & Details (`viewall.php`, `[category].php`, `order.php`)
*   **Product Grids:** Products are displayed in responsive grids, with options to filter by category or search results.
*   **Product Card:** Each product card shows image, name, brand, price, and quick actions (Add to Cart, Buy Now).
*   **Product Detail Page (`order.php`):**
    *   **Multi-Image Gallery:** View product from different angles with up to 5 images and a thumbnail selector.
    *   **Detailed Information:** Comprehensive description, brand, pricing (with original price and discount percentage), stock status, and delivery info.
    *   **Quantity Selector:** Adjust desired quantity.
    *   **Actions:** "Add to Bag" (AJAX-powered) and "Order Now" (direct checkout).
    *   **Wishlist Toggle:** Add/remove product from wishlist.
    *   **Reviews Section:** Read existing customer reviews, ratings, and admin replies.
    *   **Similar Products:** AI-driven suggestions for similar items.

### 5. Shopping Cart (`addcart.php`)
*   **Cart Overview:** List of all items added to the cart.
*   **Quantity Adjustment:** Easily change item quantities.
*   **Item Removal:** Remove individual items from the cart.
*   **Selective Checkout:** Users can select specific items from their cart to proceed to checkout.
*   **Individual Buy Now:** Option to buy a single item directly from the cart.

### 6. Checkout (`checkout.php`)
*   **Shipping Details:** Pre-filled user information (name, mobile, address) which can be edited.
*   **Payment Method:** Choose between Cash on Delivery (COD) or Online Payment.
*   **Razorpay Integration:** For online payments, a secure Razorpay popup handles the transaction.
*   **Order Confirmation:** Upon successful payment/COD selection, the order is placed.

### 7. My Orders (`myorder.php`, `order-details.php`)
*   **Order History:** A list of all past orders with order ID, date, amount, and current status.
*   **Order Details (`order-details.php`):**
    *   **Detailed Summary:** View shipping address, payment method, and product details for a specific order.
    *   **Status Line:** Visual representation of order progress (Placed, Shipped, Delivered).
    *   **Review & Rating:** Submit a review, rating, and even a photo for a purchased product.

### 8. Wishlist (`wishlist.php`)
*   **Favorite Products:** A dedicated page to view all products added to the wishlist.
*   **Quick Actions:** Easily view product details or remove items from the wishlist.

### 9. Profile Management (`my-profile.php`, `user_edit.php`)
*   **Profile Dashboard:** Displays user's name, email, and profile picture.
*   **Account Settings (`user_edit.php`):** Edit personal information, including name, mobile, address, city, and update profile photo using an integrated cropping tool.
*   **Activity Links:** Quick access to Wishlist, Cart, Orders, About Us, and Contact Us.

### 10. Contact Us (`contact.php`)
*   **Query Submission:** Users can submit messages to the admin with subject and detailed message.
*   **Conversation History:** View all past queries and the admin's replies, providing a transparent communication log.

### 11. AI Chatbot (Integrated in `footer.php`)
*   **Instant Support:** A floating chat button provides access to an AI chatbot (powered by Gemini API) for quick answers to common questions or assistance.

## ⚙️ Admin Panel Flow

### 1. Admin Login (`adminlogin.php`)
*   **Secure Access:** Dedicated login page for administrators.

### 2. Admin Dashboard (`admindashboard.php`)
*   **Overview:** Key statistics like total users, products, orders, and average store rating.
*   **Recent Activity:** Quick view of latest orders.

### 3. Product Management (`product.php`, `addproduct.php`, `modifyproduct.php`)
*   **Inventory List:** View all products with details like stock, price, category, and featured status.
*   **Filtering & Search:** Filter products by category, stock status, and search by name/brand.
*   **Add Product (`addproduct.php`):** Form to add new products with multiple images, descriptions, pricing, stock, and featured flag.
*   **Edit Product (`modifyproduct.php`):** Update any detail of an existing product, including images.
*   **Delete Product (`productdelet.php`):** Remove products from the database.

### 4. Order Management (`order.php`, `order-details.php`)
*   **Order List:** View all customer orders with order ID, customer name, amount, payment method, and current status.
*   **Order Details (`order-details.php`):** Detailed view of a specific order, including shipping address, product details, and payment information.
*   **Update Status:** Change order status (e.g., "Shipped", "Delivered") and add tracking IDs.

### 5. User Management (`user-record.php`, `user-details.php`)
*   **Customer List:** Browse all registered users, with search by name, email, or mobile.
*   **User Details (`user-details.php`):** View comprehensive user profiles, including contact details, full address, order history, and an audit log of profile changes.

### 6. Customer Queries (`queries.php`, `view-query.php`)
*   **Query Overview:** List of all customer queries, grouped by user, showing latest activity and pending messages.
*   **View Chat (`view-query.php`):** Engage in a conversation with the customer, viewing their messages and sending replies.

### 7. Review Management (`review.php`, `review-reply.php`)
*   **Review List:** View all product reviews submitted by users, including product name, customer email, rating, and comment.
*   **Reply to Review (`review-reply.php`):** Add an official admin response to a customer review.
*   **Delete Review (`delete-review.php`):** Remove reviews.

### 8. AI Statistics (`admin_stats.php`)
*   **Data-Driven Insights:** Access statistics generated by the Python AI backend:
    *   Most Ordered Products
    *   Most Wishlisted Products
    *   Lost Sales (products in cart but not ordered)
    *   Top Categories by Revenue
*   **Admin Token Security:** Access to these stats is protected by an admin token.

## 💻 Setup & Deployment

This project requires both a PHP web server (like Apache with PHP) and a Python Flask server running concurrently, along with a MySQL database.

1.  **PHP Environment:** Ensure PHP (7.4+ recommended), Apache/Nginx, and MySQL are installed and configured.
2.  **Python Environment:** Install Python (3.8+ recommended) and the required libraries (`pip install -r stylevana_ai/requirements.txt`).
3.  **Database:** Import `shivi-stylevana.sql` into your MySQL server. Update `user-page/config.php`, `admin-page/config.php`, and `stylevana_ai/db_config.py` with your database credentials.
4.  **Run Python AI Server:** Navigate to `stylevana_ai/` and run `python app.py`. This will start the Flask server, typically on `http://localhost:5000`.
5.  **Configure PHP:** Update `user-page/recommendation_widget.js` (API_BASE) and `user-page/chat_helper.php` (Gemini API Key) with correct URLs/keys.
6.  **Web Server Configuration:** Configure your web server (Apache/Nginx) to serve the PHP files and proxy requests to the Flask API for AI functionalities.

## 🚀 How to Use the Website

### For Customers:

1.  **Browse:** Start from the homepage (`index.php`) to explore categories and featured products.
2.  **Login/Register:** Click "Login / Sign Up" to create an account or log in. Remember to verify with the OTP sent to your email.
3.  **Shop:** Once logged in, use the navigation bar or category bubbles to find products. Use the search bar for specific items.
4.  **Product Details:** Click on any product to see more images, descriptions, and options.
5.  **Add to Cart/Buy Now:** Add items to your cart or proceed directly to checkout.
6.  **Checkout:** Review your order, confirm shipping details, and choose your payment method.
7.  **My Orders:** Track your purchases and leave reviews for products you've received.
8.  **Profile:** Manage your personal information and profile picture.
9.  **Chat:** Use the floating chat icon for quick assistance from the AI chatbot.

### For Administrators:

1.  **Login:** Access the admin panel via `adminlogin.php` with your admin credentials.
2.  **Dashboard:** Get an overview of your store's performance.
3.  **Manage Products:** Use the "Inventory" section to add new products, edit existing ones, or manage stock.
4.  **Manage Orders:** In the "Orders" section, view all customer orders and update their statuses as they progress.
5.  **Manage Users:** The "Customers" section allows you to view user details and their activity.
6.  **Respond to Queries:** Check "Queries" to see customer messages and send replies.
7.  **Monitor Reviews:** In "Reviews," you can moderate customer feedback and respond to them.
8.  **Analyze Data:** Visit "Statistics" to gain insights from the AI recommendation engine.

---

This README provides a comprehensive guide to Shivi's Stylevana, highlighting its features, underlying technologies, and operational flow for both users and administrators.

---

# Shivis Stylevana — AI Recommendation System
## Complete Setup Guide (Internal)

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

Apne `product_detail.php` (order.php) mein yeh add karo:

```php
<!-- Jahan recommendations dikhani ho wahan yeh div daalo -->
<div id="sv-recommendations"></div>

<!-- Footer se pehle yeh script lagao -->
<script src="recommendation_widget.js"></script>
<script>
StylevanaRec.init({
    userEmail:  '<?= isset($_SESSION["user"]) ? $_SESSION["user"] : "" ?>',
    currentPid: '<?= $pid ?>',
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
    currentPid: '<?= $pid ?>',
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
    userEmail: '<?= isset($_SESSION["user"]) ? $_SESSION["user"] : "" ?>',
    title:     'Trending Products',
    subtitle:  'Sabse popular cheezein',
    limit:     8
});
</script>
```

---

## ⚙️ STEP 5: Admin Dashboard

`admin_stats.php` browser mein kholao ya apne admin panel mein include karo.

Admin token change karo (security ke liye):
- `app.py` mein: `ADMIN_TOKEN = 'apna_secret_token'`
- `admin_stats.php` mein: `const ADMIN_TOKEN = 'apna_secret_token'`

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
