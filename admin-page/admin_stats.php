<?php 
include("function.php"); 
session_start();

if(!isset($_SESSION["admin"])) { 
    header("location:adminlogin.php"); 
    exit();
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stylevana Admin | AI Statistics</title>
    <link rel="stylesheet" href="admindashboardstyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        /* matching your peach/rose theme */
        :root { --rose: #c0396e; --soft-peach: #fdf8f6; }
        
        .ai-section { background: #fff; border-radius: 20px; padding: 25px; margin-bottom: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); }
        .ai-section h2 { font-size: 1.1rem; color: #333; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        
        .ai-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        .ai-table th { text-align: left; padding: 12px; border-bottom: 2px solid var(--soft-peach); color: #888; font-size: 0.75rem; text-transform: uppercase; }
        .ai-table td { padding: 15px 12px; border-bottom: 1px solid var(--soft-peach); vertical-align: middle; }
        
        .badge-ai { padding: 4px 10px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
        .badge-pink { background: #fde8f0; color: var(--rose); }
        .badge-purple { background: #f3eafd; color: #7b1fa2; }
        .badge-orange { background: #fff3e0; color: #f57c00; }
        .badge-green { background: #e6f9f1; color: #22a86e; }

        .bar-wrap { background: #f5eefa; border-radius: 50px; height: 7px; width: 80px; }
        .bar-fill { height: 100%; border-radius: 50px; background: var(--rose); transition: 0.5s; }
        
        .refresh-btn-ai { background: var(--rose); color: white; border: none; padding: 10px 20px; border-radius: 10px; cursor: pointer; float: right; }
    </style>
</head>
<body>

    <?php include("header.php"); ?>

    <div class="admin-wrapper">
        <?php include("sidebar.php"); ?>

        <main class="main-content">
            <div class="welcome-header">
                <button class="refresh-btn-ai" onclick="loadStats()"><i class="fas fa-sync-alt"></i> Refresh Data</button>
                <h1>✨ AI Recommendation Insights</h1>
                <p>Dashboard / AI Stats</p>
            </div>

            <!-- Summary Cards -->
            <div class="stats-grid" id="summary-cards">
                <div class="stat-card"><span>Loading...</span></div>
            </div>

            <!-- 1. Most Ordered (SAARE FEATURES) -->
            <div class="ai-section">
                <h2><span>🛍️</span> Most order products</h2>
                <div id="most-ordered-area"><p style="text-align:center; color:#ccc;">Fetching...</p></div>
            </div>

            <!-- 2. Most Wishlisted -->
            <div class="ai-section">
                <h2><span>❤️</span> Most Wishlisted Products</h2>
                <div id="most-wishlisted-area"></div>
            </div>

            <!-- 3. Lost Sales -->
            <div class="ai-section">
                <h2><span>⚠️</span>Are in cart but not ordered (Lost Sales)</h2>
                <div id="lost-sales-area"></div>
            </div>

            <!-- 4. Top Categories -->
            <div class="ai-section">
                <h2><span>📊</span> Top Categories by Revenue</h2>
                <div id="top-categories-area"></div>
            </div>

        </main>
    </div>

    <?php include("footer.php"); ?>

    <script>
    const API_BASE = 'http://localhost:5000';
    const ADMIN_TOKEN = 'shivis_admin_2024';

    async function loadStats() {
    try {
        console.log("Fetching data from Python..."); // Debugging line
        const res = await fetch(`${API_BASE}/admin/stats?token=${ADMIN_TOKEN}`);
        const data = await res.json();
        console.log("Data received:", data); // Isse console mein data dikhega
        renderAll(data);
    } catch (err) {
        console.error("Fetch Error:", err);
        document.getElementById('most-ordered-area').innerHTML = `<p style="color:red; text-align:center;">Error: ${err.message}</p>`;
    }
}

    function renderAll(data) {
        const mo = data.most_ordered || [];
        const mw = data.most_wishlisted || [];
        const ls = data.lost_sales || [];
        const tc = data.top_categories || [];

        // 1. Summary Cards
        document.getElementById('summary-cards').innerHTML = `
            <div class="stat-card"><i class="fas fa-shopping-bag"></i><h2>${mo[0]?.productname || '—'}</h2><span>Top Ordered</span></div>
            <div class="stat-card"><i class="fas fa-heart"></i><h2>${mw[0]?.wishlist_count || 0}</h2><span>Most Wishlisted</span></div>
            <div class="stat-card"><i class="fas fa-shopping-cart"></i><h2>${ls.length}</h2><span>Lost Sales</span></div>
            <div class="stat-card"><i class="fas fa-indian-rupee-sign"></i><h2>${tc[0]?.category || '—'}</h2><span>Top Category</span></div>
        `;

        // 2. Most Ordered Table
        let moHtml = `<table class="ai-table"><tr><th>Product</th><th>Category</th><th>Orders</th><th>Popularity</th></tr>`;
        const maxOrders = Math.max(...mo.map(r => r.order_count || 0), 1);
        mo.forEach(r => {
            moHtml += `<tr>
                <td style="font-weight:600; color:var(--rose);">${r.productname}</td>
                <td><span class="badge-ai badge-pink">${r.category}</span></td>
                <td>${r.order_count} Orders</td>
                <td><div class="bar-wrap"><div class="bar-fill" style="width:${(r.order_count/maxOrders)*100}%"></div></div></td>
            </tr>`;
        });
        document.getElementById('most-ordered-area').innerHTML = moHtml + `</table>`;

        // 3. Most Wishlisted
        let mwHtml = `<table class="ai-table"><tr><th>Product</th><th>Wishlisted</th><th>Interest</th></tr>`;
        const maxWish = Math.max(...mw.map(r => r.wishlist_count || 0), 1);
        mw.forEach(r => {
            mwHtml += `<tr>
                <td style="font-weight:600;">${r.productname}</td>
                <td style="color:#7b1fa2;">❤️ ${r.wishlist_count}</td>
                <td><div class="bar-wrap"><div class="bar-fill" style="width:${(r.wishlist_count/maxWish)*100}%; background:#7b1fa2;"></div></div></td>
            </tr>`;
        });
        document.getElementById('most-wishlisted-area').innerHTML = mwHtml + `</table>`;

        // 4. Lost Sales
        let lsHtml = `<table class="ai-table"><tr><th>Product</th><th>Category</th><th>Price</th><th>In Cart</th></tr>`;
        ls.forEach(r => {
            lsHtml += `<tr>
                <td><strong>${r.productname}</strong></td>
                <td><span class="badge-ai badge-orange">${r.category}</span></td>
                <td>₹${r.productprice}</td>
                <td style="color:#f57c00;">🛒 ${r.cart_count} times</td>
            </tr>`;
        });
        document.getElementById('lost-sales-area').innerHTML = ls.length ? lsHtml + `</table>` : "<p>No lost sales!</p>";

        // 5. Categories
        let tcHtml = `<table class="ai-table"><tr><th>Category</th><th>Revenue</th><th>Share</th></tr>`;
        const maxRev = Math.max(...tc.map(r => parseFloat(r.total_revenue)||0), 1);
        tc.forEach(r => {
            tcHtml += `<tr>
                <td style="text-transform:capitalize;"><strong>${r.category}</strong></td>
                <td style="color:#22a86e; font-weight:bold;">₹${parseFloat(r.total_revenue).toLocaleString('en-IN')}</td>
                <td><div class="bar-wrap"><div class="bar-fill" style="width:${(r.total_revenue/maxRev)*100}%; background:#22a86e;"></div></div></td>
            </tr>`;
        });
        document.getElementById('top-categories-area').innerHTML = tcHtml + `</table>`;
    }

    window.onload = loadStats;
    </script>
</body>
</html>