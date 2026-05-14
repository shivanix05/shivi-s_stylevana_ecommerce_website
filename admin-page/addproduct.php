<?php
include("function.php");
session_start();
if(!isset($_SESSION["admin"])) { header("location:adminlogin.php"); exit(); }
$cn = make_connection();

// ── You need these columns in your shop table for extra photos.
// Run this SQL once in phpMyAdmin if not already added:
// ALTER TABLE shop ADD COLUMN photo2 VARCHAR(300) DEFAULT NULL;
// ALTER TABLE shop ADD COLUMN photo3 VARCHAR(300) DEFAULT NULL;
// ALTER TABLE shop ADD COLUMN photo4 VARCHAR(300) DEFAULT NULL;
// ALTER TABLE shop ADD COLUMN photo5 VARCHAR(300) DEFAULT NULL;

$success = false;
$error   = '';

if(isset($_POST["btn_save"])) {
    $pname       = mysqli_real_escape_string($cn, trim($_POST["pname"]));
    $brand       = mysqli_real_escape_string($cn, trim($_POST["brand"]));
    $cat         = mysqli_real_escape_string($cn, $_POST["pcat"]);
    $desc        = mysqli_real_escape_string($cn, trim($_POST["pdesc"]));
    $price       = (float)$_POST["pprice"];
    $old_price   = (float)($_POST["old_price"] ?? 0);
    $offer       = mysqli_real_escape_string($cn, trim($_POST["offer"]));
    $stock       = (int)$_POST["pstock"];
    $delivery    = mysqli_real_escape_string($cn, $_POST["delivery"]);
    $is_featured = isset($_POST["is_featured"]) ? 1 : 0;

    // ── Main image (required) ──
    $main_fn  = $_FILES["pimage"]["name"] ?? '';
    $main_tmp = $_FILES["pimage"]["tmp_name"] ?? '';
    $allowed  = ['jpg','jpeg','png','webp','gif'];

    if(empty($main_fn)) {
        $error = "Main product image is required.";
    } else {
        $ext = strtolower(pathinfo($main_fn, PATHINFO_EXTENSION));
        if(!in_array($ext, $allowed)) {
            $error = "Only JPG, PNG, WEBP, GIF images are allowed.";
        }
    }

    if(!$error) {
        $main_path = "product_images/" . time() . "_" . basename($main_fn);
        if(!move_uploaded_file($main_tmp, $main_path)) {
            $error = "Main image upload failed. Check folder permissions on product_images/.";
        }
    }

    // ── Extra images (photo2–photo5, optional) ──
    $extra_paths = ['', '', '', ''];
    if(!$error) {
        $extra_slots = ['pimage2','pimage3','pimage4','pimage5'];
        foreach($extra_slots as $si => $slot) {
            $fn  = $_FILES[$slot]["name"]     ?? '';
            $tmp = $_FILES[$slot]["tmp_name"] ?? '';
            if(!empty($fn) && !empty($tmp) && is_uploaded_file($tmp)) {
                $ext2 = strtolower(pathinfo($fn, PATHINFO_EXTENSION));
                if(in_array($ext2, $allowed)) {
                    $epath = "product_images/" . time() . "_" . ($si+2) . "_" . basename($fn);
                    if(move_uploaded_file($tmp, $epath)) {
                        $extra_paths[$si] = $epath;
                    }
                }
            }
        }
    }

    if(!$error) {
        $ep2 = mysqli_real_escape_string($cn, $extra_paths[0]);
        $ep3 = mysqli_real_escape_string($cn, $extra_paths[1]);
        $ep4 = mysqli_real_escape_string($cn, $extra_paths[2]);
        $ep5 = mysqli_real_escape_string($cn, $extra_paths[3]);

        $q = "INSERT INTO shop 
              (productname, brand_name, productphoto, productdescription, category,
               productprice, original_price, offer_text, delivery_type, stock_qty,
               is_featured, photo2, photo3, photo4, photo5)
              VALUES
              ('$pname','$brand','$main_path','$desc','$cat',
               '$price','$old_price','$offer','$delivery','$stock',
               '$is_featured','$ep2','$ep3','$ep4','$ep5')";

        if(mysqli_query($cn, $q)) {
            $success = true;
        } else {
            // If photo2-photo5 columns don't exist yet, fallback insert without them
            $err_msg = mysqli_error($cn);
            if(strpos($err_msg, 'Unknown column') !== false) {
                $q2 = "INSERT INTO shop 
                       (productname, brand_name, productphoto, productdescription, category,
                        productprice, original_price, offer_text, delivery_type, stock_qty, is_featured)
                       VALUES
                       ('$pname','$brand','$main_path','$desc','$cat',
                        '$price','$old_price','$offer','$delivery','$stock','$is_featured')";
                if(mysqli_query($cn, $q2)) {
                    $success = true;
                } else {
                    $error = "Database error: " . mysqli_error($cn);
                }
            } else {
                $error = "Database error: " . $err_msg;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product — Stylevana Admin</title>
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
      --red:    #e74c3c;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: var(--bg); font-family: 'DM Sans', sans-serif; color: var(--dark); }

    .admin-layout { display: flex; min-height: 100vh; }
    .main-area    { flex: 1; padding: 32px; }

    /* Topbar */
    .topbar { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 28px; gap: 16px; flex-wrap: wrap; }
    .topbar h1 { font-family: 'Playfair Display', serif; font-size: 26px; color: var(--dark); }
    .topbar p  { font-size: 13px; color: var(--muted); margin-top: 3px; }
    .back-link {
      display: inline-flex; align-items: center; gap: 6px;
      color: var(--muted); font-size: 13px; text-decoration: none;
      background: var(--card); padding: 10px 18px; border-radius: 10px;
      border: 1px solid var(--border); transition: all .2s; white-space: nowrap;
    }
    .back-link:hover { color: var(--dark); border-color: var(--rose); }

    /* Alerts */
    .alert {
      display: flex; align-items: center; gap: 12px;
      padding: 14px 20px; border-radius: 14px; font-size: 13px;
      font-weight: 600; margin-bottom: 22px;
    }
    .alert.success { background: #e8f5e9; color: var(--green); border: 1px solid #c8e6c9; }
    .alert.error   { background: #fdecea; color: var(--red);   border: 1px solid #ffcdd2; }
    .alert i { font-size: 16px; }

    /* SQL hint box */
    .sql-hint {
      background: #fff8e1; border: 1px solid #ffe082; border-radius: 14px;
      padding: 14px 18px; font-size: 12px; color: #795548;
      margin-bottom: 22px; line-height: 1.7;
    }
    .sql-hint strong { color: #5d4037; display: block; margin-bottom: 4px; }
    .sql-hint code { background: #fff3e0; padding: 2px 6px; border-radius: 4px; font-size: 11px; }

    /* Form card */
    .form-card { background: var(--card); border-radius: 24px; padding: 36px; box-shadow: 0 2px 16px rgba(0,0,0,.06); }

    .sec-title {
      font-family: 'Playfair Display', serif; font-size: 16px; color: var(--dark);
      margin-bottom: 18px; padding-bottom: 10px; border-bottom: 1.5px solid var(--border);
      display: flex; align-items: center; gap: 8px;
    }
    .sec-title i { color: var(--rose); }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 28px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group.full { grid-column: 1 / -1; }

    label {
      font-size: 11px; font-weight: 700; color: var(--muted);
      text-transform: uppercase; letter-spacing: 1px;
    }
    input[type="text"],
    input[type="number"],
    select,
    textarea {
      width: 100%; padding: 12px 14px;
      border: 1.5px solid var(--border); border-radius: 12px;
      font-family: 'DM Sans', sans-serif; font-size: 14px;
      color: var(--dark); background: #fafbff; outline: none;
      transition: border-color .2s, box-shadow .2s;
    }
    input:focus, select:focus, textarea:focus {
      border-color: var(--rose); box-shadow: 0 0 0 3px rgba(217,162,153,.12);
    }
    textarea { resize: vertical; min-height: 100px; }

    /* Discount preview */
    .disc-preview {
      background: #fafbff; border: 1.5px dashed var(--border); border-radius: 12px;
      padding: 12px 16px; font-size: 13px; color: var(--muted); margin-top: 4px;
    }
    .disc-preview .good { color: var(--green); font-weight: 700; }
    .disc-preview .bad  { color: var(--red); }

    /* ── PHOTO UPLOAD GRID ── */
    .photos-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 14px;
      margin-bottom: 28px;
    }
    .photo-slot { display: flex; flex-direction: column; gap: 6px; }
    .photo-slot .slot-label {
      font-size: 10px; font-weight: 700; color: var(--muted);
      text-transform: uppercase; letter-spacing: .8px;
      display: flex; align-items: center; gap: 5px;
    }
    .slot-label .req { color: var(--rose); font-size: 12px; }
    .slot-label .opt { color: #bbb; font-size: 10px; }

    .upload-box {
      aspect-ratio: 1 / 1; border: 2px dashed var(--border);
      border-radius: 16px; cursor: pointer; position: relative;
      background: #fafbff; overflow: hidden;
      transition: border-color .2s, background .2s;
      display: flex; flex-direction: column;
      align-items: center; justify-content: center; gap: 6px;
    }
    .upload-box:hover { border-color: var(--rose); background: #fff9f8; }
    .upload-box.has-img { border-color: var(--rose); border-style: solid; }
    .upload-box input[type="file"] {
      position: absolute; inset: 0; opacity: 0;
      cursor: pointer; width: 100%; height: 100%; z-index: 2;
    }
    .upload-box .uico { font-size: 24px; color: var(--rose); }
    .upload-box .utxt { font-size: 10px; color: var(--muted); text-align: center; line-height: 1.4; }
    .upload-box img.thumb-preview {
      position: absolute; inset: 0; width: 100%; height: 100%;
      object-fit: cover; border-radius: 14px; z-index: 1;
    }
    .upload-box .remove-btn {
      position: absolute; top: 5px; right: 5px; z-index: 3;
      width: 22px; height: 22px; border-radius: 50%;
      background: var(--red); color: white; border: none;
      font-size: 10px; cursor: pointer; display: none;
      align-items: center; justify-content: center;
    }
    .upload-box.has-img .remove-btn { display: flex; }
    .upload-box.has-img .uico,
    .upload-box.has-img .utxt { display: none; }

    /* Featured checkbox */
    .feat-check {
      display: flex; align-items: center; gap: 12px;
      background: #fff9f8; border: 1.5px solid #fce8e6;
      border-radius: 12px; padding: 14px 18px; cursor: pointer;
    }
    .feat-check input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--rose); flex-shrink: 0; }
    .feat-check .fc-label { font-size: 13px; font-weight: 600; color: var(--dark); }
    .feat-check .fc-sub   { font-size: 11px; color: var(--muted); }

    /* Submit */
    .submit-row { display: flex; align-items: center; gap: 14px; margin-top: 10px; }
    .btn-submit {
      padding: 14px 36px; background: var(--dark); color: white;
      border: none; border-radius: 14px; font-family: 'DM Sans', sans-serif;
      font-weight: 700; font-size: 15px; cursor: pointer;
      transition: background .2s, transform .2s;
      display: flex; align-items: center; gap: 10px;
    }
    .btn-submit:hover { background: var(--rose2); transform: translateY(-2px); }
    .btn-cancel {
      padding: 14px 24px; background: transparent;
      border: 1.5px solid var(--border); border-radius: 14px;
      font-family: 'DM Sans', sans-serif; font-weight: 600;
      font-size: 14px; color: var(--muted); text-decoration: none;
      transition: all .2s;
    }
    .btn-cancel:hover { border-color: var(--red); color: var(--red); }

    @media (max-width: 1024px) { .photos-grid { grid-template-columns: repeat(3,1fr); } }
    @media (max-width: 768px)  {
      .main-area { padding: 16px; }
      .form-grid { grid-template-columns: 1fr; }
      .form-group.full { grid-column: 1; }
      .photos-grid { grid-template-columns: repeat(2,1fr); }
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
        <h1>Add New Product</h1>
        <p>Fill in the details to list a product on Stylevana</p>
      </div>
      <a href="product.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Inventory</a>
    </div>

    <!-- SQL hint for extra photo columns -->
    <div class="sql-hint">
      <strong>📸 To enable 5-photo gallery on product pages, run this SQL once in phpMyAdmin:</strong>
      <code>ALTER TABLE shop ADD COLUMN photo2 VARCHAR(300) DEFAULT NULL, ADD COLUMN photo3 VARCHAR(300) DEFAULT NULL, ADD COLUMN photo4 VARCHAR(300) DEFAULT NULL, ADD COLUMN photo5 VARCHAR(300) DEFAULT NULL;</code>
      <span style="margin-top:4px;display:block;">If these columns already exist, ignore this message. Extra photos will save correctly.</span>
    </div>

    <?php if($success): ?>
    <div class="alert success">
      <i class="fas fa-check-circle"></i>
      Product listed successfully!
      <a href="product.php" style="color:var(--green);margin-left:10px;">View Inventory →</a>
      <a href="addproduct.php" style="color:var(--green);margin-left:10px;">Add Another →</a>
    </div>
    <?php endif; ?>
    <?php if($error): ?>
    <div class="alert error">
      <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>

    <div class="form-card">
      <form method="POST" enctype="multipart/form-data" id="addForm">

        <!-- ── BASIC INFO ── -->
        <div class="sec-title"><i class="fas fa-tag"></i> Basic Information</div>
        <div class="form-grid">
          <div class="form-group full">
            <label>Product Full Name <span style="color:var(--rose);">*</span></label>
            <input type="text" name="pname" placeholder="e.g. Rose Gold Layered Necklace Set" required>
          </div>
          <div class="form-group">
            <label>Brand Name</label>
            <input type="text" name="brand" placeholder="e.g. Stylevana Luxe">
          </div>
          <div class="form-group">
            <label>Category <span style="color:var(--rose);">*</span></label>
            <select name="pcat" required>
              <option value="">— Select Category —</option>
              <option value="jewellery">💎 Jewellery</option>
              <option value="skincare">✨ Skincare</option>
              <option value="clothes">👗 Clothing / Fashion</option>
              <option value="Makeup">💄 Makeup</option>
            </select>
          </div>
          <div class="form-group full">
            <label>Product Description</label>
            <textarea name="pdesc" placeholder="Describe the product — materials, how to use, benefits, size info…"></textarea>
          </div>
        </div>

        <!-- ── PRICING ── -->
        <div class="sec-title"><i class="fas fa-rupee-sign"></i> Pricing & Offer</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Selling Price (₹) <span style="color:var(--rose);">*</span></label>
            <input type="number" name="pprice" id="pprice" placeholder="0" min="0" step="0.01" required oninput="calcDisc()">
          </div>
          <div class="form-group">
            <label>Original / MRP (₹) <span style="color:var(--muted);font-weight:400;">(for discount)</span></label>
            <input type="number" name="old_price" id="pold" placeholder="Leave blank if no discount" min="0" step="0.01" oninput="calcDisc()">
          </div>
          <div class="form-group full">
            <div class="disc-preview" id="discPreview">Enter selling price and MRP to see discount preview.</div>
          </div>
          <div class="form-group">
            <label>Offer Tag Text</label>
            <input type="text" name="offer" placeholder="e.g. Flat 20% OFF, Buy 1 Get 1">
          </div>
          <div class="form-group">
            <label>Delivery Type</label>
            <select name="delivery">
              <option value="Free Shipping">🚚 Free Shipping</option>
              <option value="Standard Delivery (₹50)">Standard Delivery (₹50)</option>
              <option value="Express Delivery (₹100)">Express Delivery (₹100)</option>
            </select>
          </div>
        </div>

        <!-- ── INVENTORY ── -->
        <div class="sec-title"><i class="fas fa-boxes"></i> Inventory & Visibility</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Stock Quantity <span style="color:var(--rose);">*</span></label>
            <input type="number" name="pstock" placeholder="e.g. 50" min="0" required>
          </div>
          <div class="form-group" style="justify-content:flex-end;">
            <label>Featured</label>
            <label class="feat-check">
              <input type="checkbox" name="is_featured" value="1">
              <div>
                <div class="fc-label">⭐ Mark as Featured</div>
                <div class="fc-sub">Shows in Featured Picks section on homepage</div>
              </div>
            </label>
          </div>
        </div>

        <!-- ── PHOTOS (5 slots) ── -->
        <div class="sec-title"><i class="fas fa-images"></i> Product Photos (Up to 5)</div>
        <div class="photos-grid">

          <!-- Slot 1: Main (required) -->
          <div class="photo-slot">
            <div class="slot-label">Main Photo <span class="req">*</span></div>
            <div class="upload-box" id="box1">
              <input type="file" name="pimage" accept="image/*" required onchange="previewSlot(this,'box1')">
              <button type="button" class="remove-btn" onclick="clearSlot('box1', this.parentElement.querySelector('input'))">✕</button>
              <div class="uico"><i class="fas fa-camera"></i></div>
              <div class="utxt">Main<br>Front View</div>
            </div>
          </div>

          <!-- Slot 2 -->
          <div class="photo-slot">
            <div class="slot-label">Photo 2 <span class="opt">(optional)</span></div>
            <div class="upload-box" id="box2">
              <input type="file" name="pimage2" accept="image/*" onchange="previewSlot(this,'box2')">
              <button type="button" class="remove-btn" onclick="clearSlot('box2', this.parentElement.querySelector('input'))">✕</button>
              <div class="uico"><i class="fas fa-plus"></i></div>
              <div class="utxt">Side View</div>
            </div>
          </div>

          <!-- Slot 3 -->
          <div class="photo-slot">
            <div class="slot-label">Photo 3 <span class="opt">(optional)</span></div>
            <div class="upload-box" id="box3">
              <input type="file" name="pimage3" accept="image/*" onchange="previewSlot(this,'box3')">
              <button type="button" class="remove-btn" onclick="clearSlot('box3', this.parentElement.querySelector('input'))">✕</button>
              <div class="uico"><i class="fas fa-plus"></i></div>
              <div class="utxt">Back View</div>
            </div>
          </div>

          <!-- Slot 4 -->
          <div class="photo-slot">
            <div class="slot-label">Photo 4 <span class="opt">(optional)</span></div>
            <div class="upload-box" id="box4">
              <input type="file" name="pimage4" accept="image/*" onchange="previewSlot(this,'box4')">
              <button type="button" class="remove-btn" onclick="clearSlot('box4', this.parentElement.querySelector('input'))">✕</button>
              <div class="uico"><i class="fas fa-plus"></i></div>
              <div class="utxt">Detail / Closeup</div>
            </div>
          </div>

          <!-- Slot 5 -->
          <div class="photo-slot">
            <div class="slot-label">Photo 5 <span class="opt">(optional)</span></div>
            <div class="upload-box" id="box5">
              <input type="file" name="pimage5" accept="image/*" onchange="previewSlot(this,'box5')">
              <button type="button" class="remove-btn" onclick="clearSlot('box5', this.parentElement.querySelector('input'))">✕</button>
              <div class="uico"><i class="fas fa-plus"></i></div>
              <div class="utxt">Lifestyle / Model</div>
            </div>
          </div>

        </div><!-- /photos-grid -->

        <div class="submit-row">
          <button type="submit" name="btn_save" class="btn-submit">
            <i class="fas fa-plus-circle"></i> List on Stylevana
          </button>
          <a href="product.php" class="btn-cancel">Cancel</a>
        </div>

      </form>
    </div><!-- /form-card -->

  </div><!-- /main-area -->
</div><!-- /admin-layout -->

<script>
// Discount preview
function calcDisc() {
  var sell = parseFloat(document.getElementById('pprice').value) || 0;
  var old  = parseFloat(document.getElementById('pold').value)   || 0;
  var el   = document.getElementById('discPreview');
  if(sell > 0 && old > sell) {
    var disc = Math.round(((old - sell) / old) * 100);
    el.innerHTML = 'Customer saves <span class="good">₹' + (old-sell).toFixed(0) + ' (' + disc + '% OFF)</span> — MRP ₹' + old + ', Selling at ₹' + sell;
  } else if(sell > 0 && old > 0 && old <= sell) {
    el.innerHTML = '<span class="bad">⚠ MRP must be higher than selling price for a discount to show.</span>';
  } else {
    el.innerHTML = 'Enter selling price and MRP to see discount preview.';
  }
}

// Image slot preview
function previewSlot(input, boxId) {
  var box = document.getElementById(boxId);
  if(input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      // Remove old preview if exists
      var old = box.querySelector('img.thumb-preview');
      if(old) old.remove();
      // Add new
      var img = document.createElement('img');
      img.className = 'thumb-preview';
      img.src = e.target.result;
      box.appendChild(img);
      box.classList.add('has-img');
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Clear a slot
function clearSlot(boxId, inputEl) {
  var box = document.getElementById(boxId);
  var old = box.querySelector('img.thumb-preview');
  if(old) old.remove();
  box.classList.remove('has-img');
  // Reset file input
  inputEl.value = '';
}
</script>

<?php include("footer.php"); ?>
</body>
</html>