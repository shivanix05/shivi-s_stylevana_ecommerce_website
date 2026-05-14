/**
 * wishlist-fix.js
 * Stylevana — Wishlist Toggle Fix
 * 
 * Isko after-login.php aur order.php mein include karo:
 * <script src="wishlist-fix.js"></script>
 * 
 * Yeh dono pages ke liye kaam karta hai:
 * - after-login.php: .wish-btn[data-pid] buttons (grid mein)
 * - order.php: #wishBtn button (product page)
 */

// ── AFTER-LOGIN PAGE: Grid wish buttons ──
// PHP renderCard() mein: onclick="toggleWish(pid)"
function toggleWish(pid) {
    // Find button — try data-pid first, then onclick fallback
    var btn = document.querySelector('.wish-btn[data-pid="' + pid + '"]');
    if (!btn) btn = document.querySelector('.wish-btn[data-pid="' + String(pid) + '"]');
    
    if (!btn) {
        // Last resort: order.php style button
        orderToggleWish(pid);
        return;
    }

    var icon = btn.querySelector('i');
    var was = btn.classList.contains('wishlisted');

    // Optimistic UI
    _setWishState(btn, icon, !was);

    // Server call
    fetch('wishlist_toggle.php?pid=' + pid)
        .then(function(r) {
            if (!r.ok) throw new Error('Server error');
            return r.json();
        })
        .then(function(d) {
            _setWishState(btn, icon, d.wishlisted);
            showToast(d.wishlisted ? '💖 Added to Wishlist!' : '🤍 Removed from Wishlist');
        })
        .catch(function() {
            _setWishState(btn, icon, was); // revert
            showToast('❌ Dobara try karo');
        });
}

// ── ORDER PAGE: #wishBtn button ──
// order.php mein: onclick="toggleWish(pid)"
// Same function — detects #wishBtn automatically
function orderToggleWish(pid) {
    var btn = document.getElementById('wishBtn');
    if (!btn) return;
    var was = btn.classList.contains('wishlisted');

    // Optimistic
    if (was) {
        btn.classList.remove('wishlisted');
        btn.innerHTML = '<i class="far fa-heart"></i> Wishlist';
    } else {
        btn.classList.add('wishlisted');
        btn.innerHTML = '<i class="fas fa-heart"></i> Wishlisted';
    }

    fetch('wishlist_toggle.php?pid=' + pid)
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.wishlisted) {
                btn.classList.add('wishlisted');
                btn.innerHTML = '<i class="fas fa-heart"></i> Wishlisted';
                showToast('💖 Added to Wishlist!');
            } else {
                btn.classList.remove('wishlisted');
                btn.innerHTML = '<i class="far fa-heart"></i> Wishlist';
                showToast('🤍 Removed from Wishlist');
            }
        })
        .catch(function() {
            if (was) {
                btn.classList.add('wishlisted');
                btn.innerHTML = '<i class="fas fa-heart"></i> Wishlisted';
            } else {
                btn.classList.remove('wishlisted');
                btn.innerHTML = '<i class="far fa-heart"></i> Wishlist';
            }
        });
}

// Helper — set wish state on grid card button
function _setWishState(btn, icon, wishlisted) {
    if (wishlisted) {
        btn.classList.add('wishlisted');
        if (icon) {
            icon.className = 'fas fa-heart';
            // Pop animation
            icon.style.transform = 'scale(1.5)';
            setTimeout(function() { icon.style.transform = 'scale(1)'; }, 300);
        }
        btn.title = 'Remove from Wishlist';
    } else {
        btn.classList.remove('wishlisted');
        if (icon) icon.className = 'far fa-heart';
        btn.title = 'Add to Wishlist';
    }
}

// Toast (if not already defined in page)
if (typeof showToast === 'undefined') {
    function showToast(msg, duration) {
        duration = duration || 2500;
        var t = document.getElementById('toast');
        if (!t) {
            t = document.createElement('div');
            t.id = 'toast';
            t.style.cssText = 'position:fixed;bottom:30px;left:50%;transform:translateX(-50%) translateY(80px);background:#282c3f;color:#fff;padding:12px 24px;border-radius:50px;font-size:13px;font-weight:500;z-index:9999;transition:all .35s ease;opacity:0;pointer-events:none;white-space:nowrap;font-family:sans-serif;';
            document.body.appendChild(t);
        }
        t.textContent = msg;
        t.style.opacity = '1';
        t.style.transform = 'translateX(-50%) translateY(0)';
        setTimeout(function() {
            t.style.opacity = '0';
            t.style.transform = 'translateX(-50%) translateY(80px)';
        }, duration);
    }
}