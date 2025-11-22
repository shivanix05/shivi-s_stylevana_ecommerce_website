<html>
    <body>
        
<header class="main-header">
        <div class="container header-content">
           <div class="logo">
                <div> <img  src="logo.png" class="img-logo"></div>
               <a href="#">Shivi's<span>Stylevana</span></a>
            </div>

            <!-- Search bar in the center -->
            <div class="header-center">
                <div class="search-container">
                    <input type="text" placeholder="Search for products...">
                    <button type="button"><i class="fas fa-search"></i></button>
                </div>
            </div>

            <!-- Navigation, user profile, and icons on the right -->
            <div class="header-right">
                <nav class="main-nav">
                    <a href="after-login.php">Home</a>
                    <a href="contact.php">Contact</a>
                    
                   <form method="get" action="after-login.php" class="category-form">
    <select name="category" class="categories" onchange="this.form.submit()">
        <option value="" <?php if(!isset($_GET['category']) || $_GET['category']=="") echo "selected"; ?>>All Products</option>
        <option value="jewellery" <?php if(isset($_GET['category']) && $_GET['category']=="jewellery") echo "selected"; ?>>Jewellery</option>
        <option value="skincare" <?php if(isset($_GET['category']) && $_GET['category']=="skincare") echo "selected"; ?>>Skincare</option>
        <option value="Makeup" <?php if(isset($_GET['category']) && $_GET['category']=="Makeup") echo "selected"; ?>>Makeup</option>
        <option value="clothes" <?php if(isset($_GET['category']) && $_GET['category']=="clothes") echo "selected"; ?>>Clothes</option>
    </select>
</form>

</form>

                </nav>
                <div class="user-profile">
                    <a class="user-edit" href="user_edit.php"> <img src="https://placehold.co/40x40/DCC5B2/fff?text=UN" alt="User Profile"></a> 
                  <p><?php echo $_SESSION["user"]; ?></p>
                </div>
                <div class="header-icons">
                  <a href="myorder.php"><button class="icon-btn" name="myorders"><i class="fas fa-shopping-bag"></i></button></a>
                 
                </div>
                 <div class="header-icons">
                     <a href="addcart.php"><button class="icon-btn" name="addcart"><i class="fa-solid fa-cart-shopping"></i></button></a>
                 </div>
                <div class="header-icons">
                   <form method="post"> <button class="icon-btn" name="logoutbtn"><i class="fa-solid fa-arrow-right-from-bracket"></i></button></form>
                </div>
         </div>
     </div>
 </header>

    </body>
</html>