<?php include "template.php"; /** @var $conn */

if (is_null($_SESSION["username"])) {
    header("Location:index.php");
    $_SESSION["flash_message"] = "<div class='bg-danger'>You need to log in to access this page</div>";
}
?>
    <!DOCTYPE html>
    <html>
<head>
    <title>Cyber City - Flag Claimer</title>
</head>
<body>
<h1 class='text-primary'>Flag Claimer</h1>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
<div class="col-md-12">
    <p>Enter the flag below to claim it and get points!</p>
    <p>Flag<input type="text" name="flag" class="form-control" required="required"></p>
</div>
<input type="submit" name="formSubmit" value="Claim">
</form >
</body>
    </html>


<?php
//if (isset($_POST['login'])) {
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flag = sanitise_data($_POST['flag']);

    $flagList = $conn->query("SELECT HashedFlag,PointsValue FROM Flags");

    while ($flagData = $flagList->fetch()) {
        if (password_verify($flag, $flagData[0])) {
            $username = $_SESSION["username"];
            $userInformation = $conn->query("SELECT Username, Score FROM Users WHERE Username='$username'");
            $userData = $userInformation->fetch();
            $addedScore = $userData[1] += $flagData[1];
            $sql = "UPDATE Users SET Score=? WHERE Username=?";
            // change to UPDATE
            $stmt = $conn->prepare($sql);
            $stmt->execute([$addedScore, $username]);
            echo "added points";
        } else {
            echo "Could not find flag :(";
        }
    }
}

?>