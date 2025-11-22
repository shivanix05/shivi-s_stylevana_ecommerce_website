  <?php
$cn = mysqli_connect("localhost", "root", "123456789", "shivi-stylevana");

  ?>
  
  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shivi's Stylevana Record Admin</title>
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




.delete , .Modify {
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

.delete {
    background-color: #d9534f;
    color: white;
}

.delete:hover {
    background-color: #c9302c;
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
    <form method="post">
    <div class="container">
        <h1>Shivi's Stylevana Order Admin Panel</h>
        <div class="admin-section">
            <h2>User Record list</h2>
            <?php 
            $str = "select * from userdetail";
            $rs=mysqli_query($cn,$str);
                ?>
            <table id="orderListTable">
                <thead>
                    <tr>
                        <th>sno</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Mobile Number</th>
                        <th>city</th>
                        <th>pincode</th>
                        <th>Age</th>
                        <th>password</th>
                        <th>confirm password</th>
                        <th>Action</th>
                    </tr>
                 </thead>
                 <?php
                  while($row=mysqli_fetch_array($rs)){  
                    ?>
                    <tr>
                        <td><?php echo $row['sno'];?></td>
                         <td><?php echo $row['name'];?></td>
                        <td><?php echo $row['address'];?></td>
                        <td><?php echo $row['mobilenumber'];?></td>
                        <td><?php echo $row['city'];?></td>
                        <td><?php echo $row['pincode'];?></td>
                        <td><?php echo $row['age'];?></td>
                         <td><?php echo $row['password'];?></td>
                       <td><?php echo $row['confirmpassword'];?></td>
                        <td><button class="delete"><a href="delet.php?r=<?php echo $row['sno'];?>">Delet</a></button></td>
                        </tr>
                    <?php 
                    }
                    echo"</table>";
                  ?>
        </div>
</div>
</form>
</body>
</html>
