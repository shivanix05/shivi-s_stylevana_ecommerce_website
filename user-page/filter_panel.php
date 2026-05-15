<?php
/**
 * filter_panel.php
 * ─────────────────
 * Reusable filter sidebar panel.
 * after-login.php mein include karo — getCatMeta() aur renderFilterPanel()
 * dono function_exists se wrapped hain, koi redeclaration error nahi aayega.
 *
 * Usage:
 *   echo renderFilterPanel($db_brands, $min_price, $max_price, $db_categories, $categoryFilter);
 */

if (!function_exists('getCatMeta')) {
    function getCatMeta($cat) {
        $map = [
            'jewellery' => ['label' => 'Jewellery', 'icon' => '💎'],
            'makeup'    => ['label' => 'Makeup',    'icon' => '💄'],
            'skincare'  => ['label' => 'Skincare',  'icon' => '✨'],
            'clothes'   => ['label' => 'Fashion',   'icon' => '👗'],
            'clothing'  => ['label' => 'Fashion',   'icon' => '👗'],
        ];
        $key = strtolower(trim($cat));
        return $map[$key] ?? ['label' => ucfirst($cat), 'icon' => '🛍️'];
    }
}

if (!function_exists('renderFilterPanel')) {
    function renderFilterPanel($db_brands, $min_price, $max_price, $db_categories, $categoryFilter = '') {
        ob_start();
        ?>
        <div class="filter-header">
            <h3>
                <i class="fas fa-sliders-h" style="color:#D9A299;margin-right:7px;"></i>Filters
            </h3>
            <button class="filter-clear-btn" onclick="clearAllFilters()">Clear All</button>
        </div>

        <!-- Sort -->
        <div class="filter-section">
            <div class="filter-section-title" onclick="toggleSection(this)">
                Sort By <i class="fas fa-chevron-down"></i>
            </div>
            <div class="filter-body" style="max-height:200px;">
                <select class="sort-select" id="sort-select" onchange="loadFiltered()">
                    <option value="default">Default (Featured First)</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="newest">Newest Arrivals</option>
                    <option value="rating">Highest Rated</option>
                    <option value="discount">Biggest Discount</option>
                </select>
            </div>
        </div>

        <!-- Category -->
        <?php if (!$categoryFilter): ?>
        <div class="filter-section">
            <div class="filter-section-title" onclick="toggleSection(this)">
                Category <i class="fas fa-chevron-down"></i>
            </div>
            <div class="filter-body" style="max-height:200px;">
                <div class="filter-checkbox-list">
                    <?php foreach ($db_categories as $cat):
                        $m = getCatMeta($cat); ?>
                    <label class="filter-checkbox-item">
                        <input type="checkbox"
                               class="filter-cat"
                               name="cat_filter"
                               value="<?= htmlspecialchars($cat) ?>"
                               onchange="loadFiltered()">
                        <?= $m['icon'] ?> <?= htmlspecialchars($m['label']) ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Price Range -->
        <div class="filter-section">
            <div class="filter-section-title" onclick="toggleSection(this)">
                Price Range <i class="fas fa-chevron-down"></i>
            </div>
            <div class="filter-body" style="max-height:200px;">
                <div class="price-range-wrap">
                    <div class="price-range-inputs">
                        <input type="number"
                               id="price-min"
                               placeholder="₹ Min"
                               value="<?= (int)$min_price ?>"
                               min="<?= (int)$min_price ?>"
                               max="<?= (int)$max_price ?>"
                               onchange="syncSlider(); loadFiltered()">
                        <input type="number"
                               id="price-max"
                               placeholder="₹ Max"
                               value="<?= (int)$max_price ?>"
                               min="<?= (int)$min_price ?>"
                               max="<?= (int)$max_price ?>"
                               onchange="syncSlider(); loadFiltered()">
                    </div>
                    <input type="range"
                           class="price-range-slider"
                           id="price-slider"
                           min="<?= (int)$min_price ?>"
                           max="<?= (int)$max_price ?>"
                           value="<?= (int)$max_price ?>"
                           oninput="document.getElementById('price-max').value=this.value; loadFiltered();">
                </div>
            </div>
        </div>

        <!-- Customer Rating -->
        <div class="filter-section">
            <div class="filter-section-title" onclick="toggleSection(this)">
                Customer Rating <i class="fas fa-chevron-down"></i>
            </div>
            <div class="filter-body" style="max-height:200px;">
                <div class="rating-filter-list">
                    <label class="rating-filter-item">
                        <input type="radio" name="rating_filter" value="" onchange="loadFiltered()" checked>
                        All Ratings
                    </label>
                    <label class="rating-filter-item">
                        <input type="radio" name="rating_filter" value="4" onchange="loadFiltered()">
                        <span class="rating-stars">★★★★☆</span> 4+ Stars
                    </label>
                    <label class="rating-filter-item">
                        <input type="radio" name="rating_filter" value="3" onchange="loadFiltered()">
                        <span class="rating-stars">★★★☆☆</span> 3+ Stars
                    </label>
                    <label class="rating-filter-item">
                        <input type="radio" name="rating_filter" value="2" onchange="loadFiltered()">
                        <span class="rating-stars">★★☆☆☆</span> 2+ Stars
                    </label>
                </div>
            </div>
        </div>

        <!-- Brand -->
        <?php if (!empty($db_brands)): ?>
        <div class="filter-section">
            <div class="filter-section-title" onclick="toggleSection(this)">
                Brand <i class="fas fa-chevron-down"></i>
            </div>
            <div class="filter-body" style="max-height:180px;overflow-y:auto;">
                <div class="filter-checkbox-list">
                    <?php foreach ($db_brands as $brand): ?>
                    <label class="filter-checkbox-item">
                        <input type="checkbox"
                               class="filter-brand"
                               name="brand_filter"
                               value="<?= htmlspecialchars($brand) ?>"
                               onchange="loadFiltered()">
                        <?= htmlspecialchars($brand) ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Availability -->
        <div class="filter-section">
            <div class="filter-section-title" onclick="toggleSection(this)">
                Availability <i class="fas fa-chevron-down"></i>
            </div>
            <div class="filter-body" style="max-height:120px;">
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div class="toggle-wrap">
                        In Stock Only
                        <label class="toggle-switch">
                            <input type="checkbox" id="instock-toggle" onchange="loadFiltered()">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="toggle-wrap">
                        Featured Only
                        <label class="toggle-switch">
                            <input type="checkbox" id="featured-toggle" onchange="loadFiltered()">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="toggle-wrap">
                        On Sale Only
                        <label class="toggle-switch">
                            <input type="checkbox" id="sale-toggle" onchange="loadFiltered()">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <button class="filter-apply-btn" onclick="loadFiltered()">
            <i class="fas fa-check-circle"></i> Apply Filters
        </button>
        <?php
        return ob_get_clean();
    }
}