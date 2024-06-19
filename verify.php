<?php require_once('header.php'); ?>

<?php
$error_message = ''; // Initialize error message variable
$success_message = ''; // Initialize success message variable

if(isset($_REQUEST['email']) && !isset($_REQUEST['token'])) {
    // Check if the token is correct and match with the database.
    $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
    $statement->execute(array($_REQUEST['email']));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    if(count($result) > 0) {
        foreach ($result as $row) {
            if($_REQUEST['token'] != $row['cust_token']) {
                header('location: '.BASE_URL);
                exit;
            }
        }

        // Everything is correct. Now activate the user by removing the token value from the database.
        $statement = $pdo->prepare("UPDATE tbl_customer SET cust_token=?, cust_status=? WHERE cust_email=?");
        $statement->execute(array('', 1, $_REQUEST['email']));

        $success_message = '<p style="color:green;">Your email is verified successfully. You can now login to our website.</p><p><a href="'.BASE_URL.'login.php" style="color:#167ac6;font-weight:bold;">Click here to login</a></p>';
    }
}

?>

<div class="page-banner" style="background-color:#444;">
    <div class="inner">
        <h1>Registration Successful</h1>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="user-content">
                    <?php 
                        echo $error_message;
                        echo $success_message;
                    ?>
                </div>                
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
