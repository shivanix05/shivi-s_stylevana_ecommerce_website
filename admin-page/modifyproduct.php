<?php
include("function.php");
session_start();

if(!isset($_SESSION["admin"])) { 
    header("location:adminlogin.php"); 
    exit(); 
}

$cn = make_connection();

if(isset($_GET["pid"])) {
    $pid = mysqli_real_escape_string($cn, $_GET["pid"]);
    $res = mysqli_query($cn, "SELECT * FROM shop WHERE pid='$pid'");
    $row = mysqli_fetch_array($res);
    if(!$row) { header("location:product.php"); exit(); }
} else {
    header("location:product.php");
    exit();
}

$success = false;
$error   = '';

if(isset($_POST["btn_update"])) {
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

    $allowed = ['jpg','jpeg','png','webp','gif'];

    // ── Main image (optional on edit) ──
    $main_path = $row['productphoto']; // keep existing
    if(!empty($_FILES["pimage"]["name"])) {
        $fn  = $_FILES["pimage"]["name"];
        $tmp = $_FILES["pimage"]["tmp_name"];
        $ext = strtolower(pathinfo($fn, PATHINFO_EXTENSION));
        if(in_array($ext, $allowed)) {
            $np = "product_images/" . time() . "_" . basename($fn);
            if(move_uploaded_file($tmp, $np)) $main_path = $np;
            else $error = "Main image upload failed.";
        } else {
            $error = "Only JPG, PNG, WEBP, GIF allowed.";
        }
    }

    // ── Extra images (photo2–photo5) ──
    // keep existing if no new file uploaded
    $extra_keys  = ['pimage2','pimage3','pimage4','pimage5'];
    $extra_cols  = ['photo2','photo3','photo4','photo5'];
    $extra_paths = [
        $row['photo2'] ?? '',
        $row['photo3'] ?? '',
        $row['photo4'] ?? '',
        $row['photo5'] ?? '',
    ];

    // "remove" checkboxes
    foreach($extra_cols as $si => $col) {
        if(isset($_POST['remove_'.$col]) && $_POST['remove_'.$col] == '1') {
            $extra_paths[$si] = '';
        }
    }

    if(!$error) {
        foreach($extra_keys as $si => $slot) {
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
        $mp  = mysqli_real_escape_string($cn, $main_path);
        $ep2 = mysqli_real_escape_string($cn, $extra_paths[0]);
        $ep3 = mysqli_real_escape_string($cn, $extra_paths[1]);
        $ep4 = mysqli_real_escape_string($cn, $extra_paths[2]);
        $ep5 = mysqli_real_escape_string($cn, $extra_paths[3]);

        $q = "UPDATE shop SET
                productname='$pname',
                brand_name='$brand',
                category='$cat',
                productprice='$price',
                original_price='$old_price',
                stock_qty='$stock',
                offer_text='$offer',
                delivery_type='$delivery',
                productdescription='$desc',
                is_featured='$is_featured',
                productphoto='$mp',
                photo2='$ep2',
                photo3='$ep3',
                photo4='$ep4',
                photo5='$ep5'
              WHERE pid='$pid'";

        if(mysqli_query($cn, $q)) {
            $success = true;
            // Refresh row
            $res2 = mysqli_query($cn, "SELECT * FROM shop WHERE pid='$pid'");
            $row  = mysqli_fetch_array($res2);
        } else {
            // Fallback without photo2-5 if columns don't exist yet
            $err_msg = mysqli_error($cn);
            if(strpos($err_msg, 'Unknown column') !== false) {
                $q2 = "UPDATE shop SET
                        productname='$pname', brand_name='$brand', category='$cat',
                        productprice='$price', original_price='$old_price',
                        stock_qty='$stock', offer_text='$offer',
                        delivery_type='$delivery', productdescription='$desc',
                        is_featured='$is_featured', productphoto='$mp'
                       WHERE pid='$pid'";
                if(mysqli_query($cn, $q2)) {
                    $success = true;
                    $res2 = mysqli_query($cn, "SELECT * FROM shop WHERE pid='$pid'");
                    $row  = mysqli_fetch_array($res2);
                } else {
                    $error = "DB Error: " . mysqli_error($cn);
                }
            } else {
                $error = "DB Error: " . $err_msg;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product — Stylevana Admin</title>
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

    .alert {
      display: flex; align-items: center; gap: 12px;
      padding: 14px 20px; border-radius: 14px; font-size: 13px;
      font-weight: 600; margin-bottom: 22px;
    }
    .alert.success { background: #e8f5e9; color: var(--green); border: 1px solid #c8e6c9; }
    .alert.error   { background: #fdecea; color: var(--red);   border: 1px solid #ffcdd2; }

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

    label { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; }
    input[type="text"], input[type="number"], select, textarea {
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
      font-size: 10px; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
    }
    .upload-box.has-img .uico,
    .upload-box.has-img .utxt { display: none; }

    .feat-check {
      display: flex; align-items: center; gap: 12px;
      background: #fff9f8; border: 1.5px solid #fce8e6;
      border-radius: 12px; padding: 14px 18px; cursor: pointer;
    }
    .feat-check input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--rose); flex-shrink: 0; }
    .feat-check .fc-label { font-size: 13px; font-weight: 600; color: var(--dark); }
    .feat-check .fc-sub   { font-size: 11px; color: var(--muted); }

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
    @media (max-width: 768px) {
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
        <h1>Edit Product</h1>
        <p>Updating: <strong><?php echo htmlspecialchars($row['productname']); ?></strong> (PID #<?php echo $pid; ?>)</p>
      </div>
      <a href="product.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Inventory</a>
    </div>

    <?php if($success): ?>
    <div class="alert success">
      <i class="fas fa-check-circle"></i>
      Product updated successfully!
      <a href="product.php" style="color:var(--green);margin-left:10px;">View Inventory →</a>
    </div>
    <?php endif; ?>
    <?php if($error): ?>
    <div class="alert error">
      <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>

    <div class="form-card">
      <form method="POST" enctype="multipart/form-data" id="editForm">

        <!-- ── BASIC INFO ── -->
        <div class="sec-title"><i class="fas fa-tag"></i> Basic Information</div>
        <div class="form-grid">
          <div class="form-group full">
            <label>Product Full Name <span style="color:var(--rose);">*</span></label>
            <input type="text" name="pname" value="<?php echo htmlspecialchars($row['productname']); ?>" required>
          </div>
          <div class="form-group">
            <label>Brand Name</label>
            <input type="text" name="brand" value="<?php echo htmlspecialchars($row['brand_name'] ?? ''); ?>">
          </div>
          <div class="form-group">
            <label>Category <span style="color:var(--rose);">*</span></label>
            <select name="pcat" required>
              <option value="">— Select Category —</option>
              <option value="jewellery" <?php echo ($row['category']==='jewellery')?'selected':''; ?>>💎 Jewellery</option>
              <option value="skincare"  <?php echo ($row['category']==='skincare') ?'selected':''; ?>>✨ Skincare</option>
              <option value="clothes"   <?php echo ($row['category']==='clothes')  ?'selected':''; ?>>👗 Clothing / Fashion</option>
              <option value="Makeup"    <?php echo ($row['category']==='Makeup')   ?'selected':''; ?>>💄 Makeup</option>
            </select>
          </div>
          <div class="form-group full">
            <label>Product Description</label>
            <textarea name="pdesc"><?php echo htmlspecialchars($row['productdescription'] ?? ''); ?></textarea>
          </div>
        </div>

        <!-- ── PRICING ── -->
        <div class="sec-title"><i class="fas fa-rupee-sign"></i> Pricing & Offer</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Selling Price (₹) <span style="color:var(--rose);">*</span></label>
            <input type="number" name="pprice" id="pprice"
                   value="<?php echo $row['productprice']; ?>"
                   min="0" step="0.01" required oninput="calcDisc()">
          </div>
          <div class="form-group">
            <label>Original / MRP (₹)</label>
            <input type="number" name="old_price" id="pold"
                   value="<?php echo $row['original_price'] ?? 0; ?>"
                   min="0" step="0.01" oninput="calcDisc()">
          </div>
          <div class="form-group full">
            <div class="disc-preview" id="discPreview">Loading discount preview…</div>
          </div>
          <div class="form-group">
            <label>Offer Tag Text</label>
            <input type="text" name="offer" value="<?php echo htmlspecialchars($row['offer_text'] ?? ''); ?>" placeholder="e.g. Flat 20% OFF">
          </div>
          <div class="form-group">
            <label>Delivery Type</label>
            <select name="delivery">
              <option value="Free Shipping" <?php echo ($row['delivery_type']==='Free Shipping')?'selected':''; ?>>🚚 Free Shipping</option>
              <option value="Standard Delivery (₹50)" <?php echo ($row['delivery_type']==='Standard Delivery (₹50)')?'selected':''; ?>>Standard Delivery (₹50)</option>
              <option value="Express Delivery (₹100)" <?php echo ($row['delivery_type']==='Express Delivery (₹100)')?'selected':''; ?>>Express Delivery (₹100)</option>
            </select>
          </div>
        </div>

        <!-- ── INVENTORY ── -->
        <div class="sec-title"><i class="fas fa-boxes"></i> Inventory & Visibility</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Stock Quantity <span style="color:var(--rose);">*</span></label>
            <input type="number" name="pstock" value="<?php echo $row['stock_qty'] ?? 0; ?>" min="0" required>
          </div>
          <div class="form-group" style="justify-content:flex-end;">
            <label>Featured</label>
            <label class="feat-check">
              <input type="checkbox" name="is_featured" value="1" <?php echo (!empty($row['is_featured']) && $row['is_featured']==1)?'checked':''; ?>>
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

          <?php
          $photo_fields = [
            ['pimage',  'productphoto', 'Main Photo',  true,  'Front View'],
            ['pimage2', 'photo2',       'Photo 2',     false, 'Side View'],
            ['pimage3', 'photo3',       'Photo 3',     false, 'Back View'],
            ['pimage4', 'photo4',       'Photo 4',     false, 'Detail / Closeup'],
            ['pimage5', 'photo5',       'Photo 5',     false, 'Lifestyle / Model'],
          ];
          foreach($photo_fields as $fi => $pf):
            $boxId    = 'box'.($fi+1);
            $existing = $row[$pf[1]] ?? '';
            $hasImg   = !empty($existing);
          ?>
          <div class="photo-slot">
            <div class="slot-label">
              <?php echo $pf[2]; ?>
              <?php if($pf[3]): ?><span class="req">*</span><?php else: ?><span class="opt">(optional)</span><?php endif; ?>
            </div>
            <div class="upload-box <?php echo $hasImg ? 'has-img' : ''; ?>" id="<?php echo $boxId; ?>">
              <input type="file" name="<?php echo $pf[0]; ?>" accept="image/*"
                     onchange="previewSlot(this,'<?php echo $boxId; ?>')">
              <button type="button" class="remove-btn"
                      onclick="clearSlot('<?php echo $boxId; ?>',
                               this.parentElement.querySelector('input[type=file]'),
                               '<?php echo ($fi>0) ? $pf[1] : ''; ?>')">✕</button>
              <?php if($hasImg): ?>
                <img src="<?php echo htmlspecialchars($existing); ?>" class="thumb-preview"
                     onerror="this.style.display='none'">
              <?php else: ?>
                <div class="uico"><i class="fas fa-<?php echo $fi===0?'camera':'plus'; ?>"></i></div>
                <div class="utxt"><?php echo $fi===0?'Main<br>':''; ?><?php echo $pf[4]; ?></div>
              <?php endif; ?>
            </div>
            <?php if($fi > 0): ?>
            <!-- Hidden remove flag -->
            <input type="hidden" name="remove_<?php echo $pf[1]; ?>" id="remove_<?php echo $pf[1]; ?>" value="0">
            <?php endif; ?>
          </div>
          <?php endforeach; ?>

        </div><!-- /photos-grid -->

        <div class="submit-row">
          <button type="submit" name="btn_update" class="btn-submit">
            <i class="fas fa-save"></i> Save Changes
          </button>
          <a href="product.php" class="btn-cancel">Discard & Return</a>
        </div>

      </form>
    </div>

  </div>
</div>

<script>
function calcDisc() {
  var sell = parseFloat(document.getElementById('pprice').value) || 0;
  var old  = parseFloat(document.getElementById('pold').value)   || 0;
  var el   = document.getElementById('discPreview');
  if(sell > 0 && old > sell) {
    var disc = Math.round(((old - sell) / old) * 100);
    el.innerHTML = 'Customer saves <span class="good">₹' + (old-sell).toFixed(0) + ' (' + disc + '% OFF)</span> — MRP ₹' + old + ', Selling at ₹' + sell;
  } else if(sell > 0 && old > 0 && old <= sell) {
    el.innerHTML = '<span class="bad">⚠ MRP must be higher than selling price for a discount.</span>';
  } else {
    el.innerHTML = 'Enter selling price and MRP to see discount preview.';
  }
}

function previewSlot(input, boxId) {
  var box = document.getElementById(boxId);
  if(input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      var old = box.querySelector('img.thumb-preview');
      if(old) old.remove();
      var img = document.createElement('img');
      img.className = 'thumb-preview';
      img.src = e.target.result;
      box.appendChild(img);
      box.classList.add('has-img');
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function clearSlot(boxId, inputEl, colName) {
  var box = document.getElementById(boxId);
  var old = box.querySelector('img.thumb-preview');
  if(old) old.remove();
  box.classList.remove('has-img');
  inputEl.value = '';
  // Set remove flag if it's an extra photo
  if(colName) {
    var flag = document.getElementById('remove_' + colName);
    if(flag) flag.value = '1';
  }
}

// Init discount preview on load
window.addEventListener('load', calcDisc);
</script>

<?php include("footer.php"); ?>
</body>
</html>