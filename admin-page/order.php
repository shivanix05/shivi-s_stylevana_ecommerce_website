<?php include("config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shivi's Stylevana Order Admin</title>
    
    <style>
         :root {
    --primary-bg: #F0E4D3;
    --primary-text: #333;
    --accent-color: #D9A299;
    --secondary-accent: #DCC5B2;
    --dark-text: #222;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--primary-bg);
    color: var(--primary-text);
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 1200px;
    margin: auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

h1, h2 {
    color: var(--dark-text);
    text-align: center;
}

h1 {
    font-size: 2.5em;
    margin-bottom: 10px;
}

h2 {
    font-size: 1.8em;
    border-bottom: 2px solid var(--secondary-accent);
    padding-bottom: 10px;
    margin-top: 40px;
}



/* Table Styling */
table {

    border-collapse: collapse;
    margin-top: 20px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden; /* To apply border-radius to the table */
}

table th, table td {
    padding: 15px;
    border-bottom: 1px solid #eee;
    text-align: left;
    font-size: small;
}

table th {
    background-color: var(--secondary-accent);
    color: var(--dark-text);
    font-weight: bold;
    text-transform: uppercase;
    font-size: medium;
    width: 100%;
}




 .Modify {
    padding: 8px 12px;
    margin-right: 8px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s;
}

.Modify {
    background-color: #f0ad4e;
    color: white;
}

.Modify:hover {
    background-color: #ec971f;
}


.td{
    font-size: small;
}
a{
    text-decoration: none;
    color: #F0E4D3;
}
    </style>
</head>

<body>
    <div class="container">
        <h1>Shivi's Stylevana Order Admin Panel</h>
        <div class="admin-section">
            <h2>User Order List</h2>
            <table id="orderListTable">
                <thead>
                    <tr class="orderrow">
                        <th>product id</th>
                        <th>user gmail</th>
                         <th>user name</th>
                        <th>User Address</th>
                        <th>product photo</th>
                        <th>Total Price</th>
                        <th>Order Date & Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                 </thead>
                 <tbody>
                <?php while ($row = mysqli_fetch_array($rs)) { ?>
                    <tr>
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo $row['product_id']; ?></td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['productprice']; ?></td>

                        <td>
                            <img src="<?php echo $row['productphoto']; ?>" alt="Product Image" width="100">
                        </td>
                        <td><?php echo $row['mobile']; ?></td>
                        <td><?php echo  $row['payment_method']; ?></td>
                        <td><?php echo $row['order_date']; ?></td>
                        <td>
                            
                            <button class="modify"><a href="modifyproduct.php?r=<?php $row['pid']; ?>">Modify</a></button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
              </table>
        </div>
</div>
</body>
</html>
