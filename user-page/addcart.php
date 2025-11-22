<?php include("config.php");
session_start();
if (!isset($_SESSION["user"])){
    header("location:login.php");
    exit();
}
?>
<?php
    if (isset($_POST["logoutbtn"])){
        session_destroy();
        header("location:login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Cart</title>
 <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="afterl-style.css">
   
<style>
        table {
            width: 80%;
            margin: 40px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }

        table thead tr {
            background-color: #DCC5B2;
            color: #333;
            text-align: left;
            font-weight: bold;
            font-size: 1.1em;
        }

        table th,
        table td {
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
        }

        table tbody tr:hover {
            background-color: #f9f9f9;
        }

        table tbody tr:last-child td {
            border-bottom: none;
        }
        .product-info-cell {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .product-info-cell img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        .remove-btn {
            background: none;
            border: none;
            color: #d9534f;
            font-size: 1.2em;
            cursor: pointer;
            transition: color 0.2s ease-in-out;
        }
        .remove-btn:hover {
            color: #c9302c;
        }
         .order-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 50px;
            background-color: var(--accent-color);
            color: #fff;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        
        .order-btn:hover {
            background-color: #a88476;
            transform: translateY(-2px);
        }
        

    </style>
</head>
<body>
   <?php include("header.php")?>
   <main>
    <h1>My Cart</h1>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Product ID</th>
                <th>Price</th>
                <th>Date & Time</th>
                <th></th>
            <th></th> </tr>
        </thead>
        <tbody id="orderTable"></tbody>
    </table>
    </main>
    <script>
        // Function to render the cart items
        function renderCart() {
            let orders = JSON.parse(localStorage.getItem("orders")) || [];
            let table = document.getElementById("orderTable");
            table.innerHTML = ""; // Clear the table content before re-rendering

            if (orders.length === 0) {
                table.innerHTML = "<tr><td colspan='5'>No items in your cart.</td></tr>";
            } else {
                orders.forEach((order, index) => {
                    let row = `<tr>
                        <td>
                            <div class="product-info-cell">
                                <img src="${order.image}" alt="${order.name}">
                                <span>${order.name}</span>
                            </div>
                        </td>
                        
                        <td>${order.pid}</td>
                        <td>Rs. ${order.price}</td>
                        <td>${order.date}</td>
                        <td><button class="remove-btn" onclick="removeItem(${index})"><i class="fas fa-times"></i></button></td>
                       
                        <td> <span><a href="order.php"><button class="order-btn">Order Now</button></a></span></td>
                    </tr>`;
                    table.innerHTML += row;
                });
            }
        }

        // Function to remove an item from the cart
        function removeItem(index) {
            let orders = JSON.parse(localStorage.getItem("orders")) || [];
            orders.splice(index, 1); // Remove one item at the specified index
            localStorage.setItem("orders", JSON.stringify(orders)); // Save the updated array
            renderCart(); // Re-render the table to show the change
        }

        // Initial render when the page loads
        renderCart();
    </script>
</body>
</html>