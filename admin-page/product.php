<?php 
include("function.php"); 
session_start();
if(!isset($_SESSION["admin"])) { header("location:adminlogin.php"); exit(); } 
$cn = make_connection();

// ── Stats (always full table, no filter) ──
$total_res   = mysqli_query($cn, "SELECT COUNT(*) as c FROM shop");
$total_prods = mysqli_fetch_assoc($total_res)['c'] ?? 0;

$out_res   = mysqli_query($cn, "SELECT COUNT(*) as c FROM shop WHERE stock_qty <= 0");
$out_count = mysqli_fetch_assoc($out_res)['c'] ?? 0;

$low_res   = mysqli_query($cn, "SELECT COUNT(*) as c FROM shop WHERE stock_qty > 0 AND stock_qty <= 5");
$low_count = mysqli_fetch_assoc($low_res)['c'] ?? 0;

$feat_res   = mysqli_query($cn, "SELECT COUNT(*) as c FROM shop WHERE is_featured = 1");
$feat_count = mysqli_fetch_assoc($feat_res)['c'] ?? 0;

// ── Build filters safely ──
$search       = isset($_GET['s'])     ? trim($_GET['s'])   : '';
$cat          = isset($_GET['cat'])   ? trim($_GET['cat']) : '';
$stock_filter = isset($_GET['stock']) ? trim($_GET['stock']) : '';

$s_esc   = mysqli_real_escape_string($cn, $search);
$cat_esc = mysqli_real_escape_string($cn, $cat);

$where = "WHERE 1=1";
if($s_esc !== '')       $where .= " AND (productname LIKE '%$s_esc%' OR brand_name LIKE '%$s_esc%')";
if($cat_esc !== '')     $where .= " AND category = '$cat_esc'";
if($stock_filter === 'out')  $where .= " AND stock_qty <= 0";
elseif($stock_filter === 'low')  $where .= " AND stock_qty > 0 AND stock_qty <= 5";
elseif($stock_filter === 'ok')   $where .= " AND stock_qty > 5";

$rs           = mysqli_query($cn, "SELECT * FROM shop $where ORDER BY pid DESC");
$result_count = mysqli_num_rows($rs);

// Categories for dropdown (distinct from table)
$cat_res = mysqli_query($cn, "SELECT DISTINCT category FROM shop WHERE category != '' AND category IS NOT NULL ORDER BY category ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Stylevana Admin — Inventory</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg:     #f4f6fb;
      --card:   #ffffff;
      --rose:   #D9A299;
      --rose2:  #c48b81;
      --dark:   #282c3f;
      --muted:  #8b92a5;
      --border: #eaedf3;
      --green:  #27ae60;
      --orange: #f39c12;
      --red:    #e74c3c;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: var(--bg); font-family: 'DM Sans', sans-serif; color: var(--dark); }

    .admin-layout { display: flex; min-height: 100vh; }
    .main-area    { flex: 1; padding: 32px; overflow-x: hidden; }

    /* Topbar */
    .topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; flex-wrap: wrap; gap: 16px; }
    .topbar h1 { font-family: 'Playfair Display', serif; font-size: 28px; color: var(--dark); margin-bottom: 2px; }
    .topbar p  { font-size: 13px; color: var(--muted); }
    .btn-add {
      display: inline-flex; align-items: center; gap: 8px;
      background: var(--dark); color: white; padding: 12px 24px;
      border-radius: 12px; font-weight: 600; font-size: 13px;
      text-decoration: none; transition: background .2s, transform .2s;
    }
    .btn-add:hover { background: var(--rose2); transform: translateY(-2px); }

    /* Stat cards */
    .stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 18px; margin-bottom: 28px; }
    .stat-card {
      background: var(--card); border-radius: 18px; padding: 22px 24px;
      box-shadow: 0 2px 12px rgba(0,0,0,.05);
      display: flex; align-items: center; gap: 16px;
      transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
    .stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
    .si-total { background: #eef2ff; color: #6c63ff; }
    .si-feat  { background: #e8f5e9; color: var(--green); }
    .si-low   { background: #fff8e1; color: var(--orange); }
    .si-out   { background: #fdecea; color: var(--red); }
    .stat-num { font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; line-height: 1; }
    .stat-lbl { font-size: 12px; color: var(--muted); margin-top: 3px; }

    /* Filter bar */
    .filter-bar {
      background: var(--card); border-radius: 16px; padding: 18px 22px;
      box-shadow: 0 2px 10px rgba(0,0,0,.04);
      display: flex; align-items: center; gap: 12px; margin-bottom: 22px; flex-wrap: wrap;
    }
    .search-wrap {
      flex: 1; min-width: 200px; display: flex; align-items: center; gap: 8px;
      background: var(--bg); border-radius: 10px; padding: 10px 14px;
      border: 1.5px solid var(--border); transition: border-color .2s;
    }
    .search-wrap:focus-within { border-color: var(--rose); }
    .search-wrap input { border: none; background: transparent; outline: none; font-size: 13px; font-family: 'DM Sans', sans-serif; color: var(--dark); width: 100%; }
    .search-wrap i { color: var(--muted); flex-shrink: 0; }
    .f-select {
      padding: 10px 14px; border-radius: 10px; border: 1.5px solid var(--border);
      background: var(--bg); font-family: 'DM Sans', sans-serif; font-size: 13px;
      color: var(--dark); outline: none; cursor: pointer; transition: border-color .2s;
    }
    .f-select:focus { border-color: var(--rose); }
    .f-btn {
      padding: 10px 20px; border-radius: 10px; border: none;
      background: var(--dark); color: white; font-family: 'DM Sans', sans-serif;
      font-weight: 600; font-size: 13px; cursor: pointer; transition: background .2s;
      display: inline-flex; align-items: center; gap: 6px;
    }
    .f-btn:hover { background: var(--rose2); }
    .f-clear {
      font-size: 12px; color: var(--muted); text-decoration: none;
      display: flex; align-items: center; gap: 4px; white-space: nowrap;
      padding: 8px 14px; border-radius: 10px; border: 1.5px solid var(--border);
      transition: all .2s;
    }
    .f-clear:hover { color: var(--red); border-color: var(--red); }
    .result-count { font-size: 12px; color: var(--muted); margin-left: auto; white-space: nowrap; }

    /* Active filter pills */
    .active-filters { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 14px; }
    .filter-pill {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--tag-bg, #fce8e6); color: var(--rose2);
      font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px;
    }

    /* Table */
    .table-card { background: var(--card); border-radius: 20px; box-shadow: 0 2px 12px rgba(0,0,0,.05); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: var(--bg); border-bottom: 1px solid var(--border); }
    th { padding: 14px 20px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); white-space: nowrap; }
    tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #fafbff; }
    td { padding: 15px 20px; vertical-align: middle; }

    .prod-cell { display: flex; align-items: center; gap: 14px; }
    .prod-thumb { width: 52px; height: 52px; border-radius: 12px; object-fit: cover; flex-shrink: 0; border: 1px solid var(--border); }
    .prod-name  { font-size: 13px; font-weight: 600; color: var(--dark); margin-bottom: 2px; }
    .prod-pid   { font-size: 11px; color: var(--muted); }
    .prod-brand { font-size: 11px; color: var(--rose2); font-weight: 600; margin-top: 2px; }

    /* Photo count indicator */
    .photo-count {
      display: inline-flex; align-items: center; gap: 3px;
      font-size: 10px; color: var(--muted); margin-top: 3px;
    }
    .photo-count i { color: var(--rose); font-size: 9px; }

    .cat-pill { display: inline-block; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; background: #eef2ff; color: #6c63ff; }

    .price-cell { font-size: 14px; font-weight: 700; color: var(--dark); }
    .price-old-sm { font-size: 11px; color: var(--muted); text-decoration: line-through; }
    .disc-sm { font-size: 10px; color: var(--green); font-weight: 700; }

    .badge { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px; }
    .badge::before { content:''; width:6px; height:6px; border-radius:50%; }
    .in-stock     { background:#e8f5e9; color:var(--green); }  .in-stock::before     { background: var(--green); }
    .low-stock    { background:#fff8e1; color:var(--orange); } .low-stock::before    { background: var(--orange); }
    .out-of-stock { background:#fdecea; color:var(--red); }    .out-of-stock::before { background: var(--red); }

    .feat-pill { font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
    .feat-pill.yes { background:#fff9e6; color:#e6a817; }
    .feat-pill.no  { background:var(--bg); color:var(--muted); }

    .actions-cell { display: flex; align-items: center; gap: 8px; }
    .action-btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none; transition: all .2s; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; }
    .action-btn.edit   { background:#eef2ff; color:#6c63ff; }
    .action-btn.edit:hover   { background:#6c63ff; color:white; }
    .action-btn.delete { background:#fdecea; color:var(--red); }
    .action-btn.delete:hover { background:var(--red); color:white; }

    .empty-state { text-align: center; padding: 60px; color: var(--muted); font-size: 14px; }
    .empty-state .emo { font-size: 42px; margin-bottom: 12px; }
    .empty-state .try-clear { display: inline-block; margin-top: 14px; color: var(--rose2); text-decoration: none; font-weight: 600; }

    @media (max-width: 1024px) { .stat-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 768px)  {
      .main-area { padding: 16px; }
      .stat-grid { grid-template-columns: repeat(2,1fr); }
      .filter-bar { flex-direction: column; align-items: stretch; }
      .result-count { margin-left: 0; }
    }
  </style>
</head>
<body>

<?php include("header.php"); ?>

<div class="admin-layout">
  <?php include("sidebar.php"); ?>

  <div class="main-area">

    <div class="topbar">
      <div>
        <h1>Product Inventory</h1>
        <p>Manage all Stylevana products from here</p>
      </div>
      <a href="addproduct.php" class="btn-add"><i class="fas fa-plus"></i> Add New Product</a>
    </div>

    <!-- Stat Cards -->
    <div class="stat-grid">
      <div class="stat-card">
        <div class="stat-icon si-total"><i class="fas fa-box-open"></i></div>
        <div><div class="stat-num"><?php echo $total_prods; ?></div><div class="stat-lbl">Total Products</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon si-feat"><i class="fas fa-star"></i></div>
        <div><div class="stat-num"><?php echo $feat_count; ?></div><div class="stat-lbl">Featured</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon si-low"><i class="fas fa-exclamation-triangle"></i></div>
        <div><div class="stat-num"><?php echo $low_count; ?></div><div class="stat-lbl">Low Stock</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon si-out"><i class="fas fa-times-circle"></i></div>
        <div><div class="stat-num"><?php echo $out_count; ?></div><div class="stat-lbl">Out of Stock</div></div>
      </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="product.php" id="filterForm">
      <div class="filter-bar">
        <div class="search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" name="s"
                 placeholder="Search product or brand…"
                 value="<?php echo htmlspecialchars($search); ?>"
                 autocomplete="off">
        </div>

        <select name="cat" class="f-select">
          <option value="">All Categories</option>
          <?php
          mysqli_data_seek($cat_res, 0);
          while($c = mysqli_fetch_assoc($cat_res)):
            $cval = $c['category'];
            $sel  = ($cat === $cval) ? 'selected' : '';
          ?>
          <option value="<?php echo htmlspecialchars($cval); ?>" <?php echo $sel; ?>>
            <?php echo htmlspecialchars(ucfirst($cval)); ?>
          </option>
          <?php endwhile; ?>
        </select>

        <select name="stock" class="f-select">
          <option value="">All Stock</option>
          <option value="ok"  <?php echo $stock_filter==='ok'  ? 'selected':''; ?>>✅ In Stock</option>
          <option value="low" <?php echo $stock_filter==='low' ? 'selected':''; ?>>⚠️ Low Stock</option>
          <option value="out" <?php echo $stock_filter==='out' ? 'selected':''; ?>>❌ Out of Stock</option>
        </select>

        <button type="submit" class="f-btn"><i class="fas fa-filter"></i> Filter</button>

        <?php if($search !== '' || $cat !== '' || $stock_filter !== ''): ?>
          <a href="product.php" class="f-clear"><i class="fas fa-times"></i> Clear All</a>
        <?php endif; ?>

        <span class="result-count">
          <strong><?php echo $result_count; ?></strong> product<?php echo $result_count!=1?'s':''; ?>
        </span>
      </div>
    </form>

    <!-- Active filter pills -->
    <?php if($search !== '' || $cat !== '' || $stock_filter !== ''): ?>
    <div class="active-filters">
      <span style="font-size:11px; color:var(--muted); font-weight:600;">Active filters:</span>
      <?php if($search !== ''): ?>
        <span class="filter-pill"><i class="fas fa-search"></i> "<?php echo htmlspecialchars($search); ?>"</span>
      <?php endif; ?>
      <?php if($cat !== ''): ?>
        <span class="filter-pill"><i class="fas fa-tag"></i> <?php echo htmlspecialchars(ucfirst($cat)); ?></span>
      <?php endif; ?>
      <?php if($stock_filter !== ''): ?>
        <span class="filter-pill"><i class="fas fa-boxes"></i>
          <?php echo $stock_filter==='ok'?'In Stock':($stock_filter==='low'?'Low Stock':'Out of Stock'); ?>
        </span>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Table -->
    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th>Featured</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if($result_count === 0): ?>
          <tr><td colspan="7">
            <div class="empty-state">
              <div class="emo">📦</div>
              <div>No products found<?php echo ($search||$cat||$stock_filter) ? ' matching your filters' : ''; ?>.</div>
              <?php if($search||$cat||$stock_filter): ?>
                <a href="product.php" class="try-clear"><i class="fas fa-times-circle"></i> Clear filters & try again</a>
              <?php endif; ?>
            </div>
          </td></tr>
          <?php else: ?>

          <?php while($row = mysqli_fetch_assoc($rs)):
            $stk = (int)$row['stock_qty'];
            if($stk <= 0)     { $badge='out-of-stock'; $btext='Out of Stock'; }
            elseif($stk <= 5) { $badge='low-stock';    $btext='Low Stock'; }
            else              { $badge='in-stock';      $btext='In Stock'; }

            $sell = (float)$row['productprice'];
            $mrp  = (!empty($row['original_price']) && (float)$row['original_price'] > 0)
                    ? (float)$row['original_price'] : $sell;
            $disc = ($mrp > $sell) ? round((($mrp - $sell) / $mrp) * 100) : 0;
            $feat = (!empty($row['is_featured']) && $row['is_featured'] == 1);

            // Count how many photos this product has
            $photo_count = 1; // main photo
            foreach(['photo2','photo3','photo4','photo5'] as $pcol) {
              if(!empty($row[$pcol])) $photo_count++;
            }
          ?>
          <tr>
            <td>
              <div class="prod-cell">
                <img src="<?php echo htmlspecialchars($row['productphoto']); ?>"
                     class="prod-thumb"
                     alt="<?php echo htmlspecialchars($row['productname']); ?>"
                     onerror="this.src='https://via.placeholder.com/52x52/f5f5f5/ccc?text=?'">
                <div>
                  <div class="prod-name"><?php echo htmlspecialchars($row['productname']); ?></div>
                  <div class="prod-pid">PID #<?php echo $row['pid']; ?></div>
                  <?php if(!empty($row['brand_name'])): ?>
                    <div class="prod-brand"><?php echo htmlspecialchars($row['brand_name']); ?></div>
                  <?php endif; ?>
                  <div class="photo-count">
                    <i class="fas fa-images"></i> <?php echo $photo_count; ?> photo<?php echo $photo_count>1?'s':''; ?>
                  </div>
                </div>
              </div>
            </td>
            <td><span class="cat-pill"><?php echo htmlspecialchars(ucfirst($row['category'])); ?></span></td>
            <td>
              <div class="price-cell">₹<?php echo number_format($sell); ?></div>
              <?php if($disc > 0): ?>
                <div class="price-old-sm">₹<?php echo number_format($mrp); ?></div>
                <div class="disc-sm"><?php echo $disc; ?>% OFF</div>
              <?php endif; ?>
            </td>
            <td style="font-weight:700; font-size:15px; color:<?php echo $stk<=0?'var(--red)':($stk<=5?'var(--orange)':'var(--dark)'); ?>">
              <?php echo $stk; ?>
            </td>
            <td><span class="badge <?php echo $badge; ?>"><?php echo $btext; ?></span></td>
            <td><span class="feat-pill <?php echo $feat?'yes':'no'; ?>"><?php echo $feat?'⭐ Yes':'— No'; ?></span></td>
            <td>
              <div class="actions-cell">
                <a href="modifyproduct.php?pid=<?php echo $row['pid']; ?>" class="action-btn edit">
                  <i class="fas fa-pen"></i> Edit
                </a>
                <a href="productdelet.php?r=<?php echo $row['pid']; ?>" class="action-btn delete"
                   onclick="return confirm('Remove this product from Stylevana?')">
                  <i class="fas fa-trash"></i>
                </a>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<?php include("footer.php"); ?>
</body>
</html>