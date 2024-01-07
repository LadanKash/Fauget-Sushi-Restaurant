<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Using form to update an external file</title>
   
       <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            margin: 0;
            padding: 0;
             flex-direction: column;
            align-items: center;
        }

        .content{
     text-align: center;
     margin-bottom: 20px;
     padding-bottom: 20px;
        }

        h1 {
            color: #333;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .error {
            color: red;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

           .social-buttons {
            display: flex;
           /* justify-content: space-around;*/
            margin-bottom: 20px;
            margin-top: 20px;
            margin-left: 460px;
        }

        .social-buttons a {
            text-decoration: none;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 25%;
            text-align: center;
        }

        .fb {
            background-color: #3b5998;
            margin-left: 10px;
        }

        .google {
            background-color: #dd4b39;
            margin-left: 10px;
        }

    </style>

</head>
<body>

<?php
$name = $email = $phone = $comment = "";
$nameError = $emailError = $phoneError = "";

$confirmationMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    $name = test_input($_POST["name"]);
    if (empty($name)) {
        $nameError = "Name is required";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $nameError = "Only letters and white space allowed";
    }

    // Validate email
    $email = test_input($_POST["email"]);
    if (empty($email)) {
        $emailError = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format";
    }

    // Validate phone 
    $phone = test_input($_POST["phone"]);
    if (!empty($phone) && !preg_match("/^[0-9]{10}$/", $phone)) {
        $phoneError = "Invalid phone format";
    }

    $comment = test_input($_POST["comment"]);

    // Append comment to file
    $file = fopen("comments.txt", "a") or die("Unable to open file");
    fwrite($file, "Name: $name\nEmail: $email\nPhone: $phone\nComment: $comment\n\n");
    fclose($file);

    // confirmation message
    $confirmationMessage = "Thank you, $name $phone! Your reservation is confirmed. We will contact you at $email.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<script>
function submitForm() {
    
    const name = document.getElementById('name').value;
    const phone = document.getElementById('phone').value;
    const email = document.getElementById('email').value;

    
    document.getElementById('reservation-form').style.display = 'none';
    document.getElementById('back-button').style.display = 'none';
    document.getElementById('next-button').style.display = 'none';

   
    const confirmationMessage = document.getElementById('confirmation-message');
    const message = `Thank you, ${name} ${phone}! Your reservation is confirmed. We will contact you at ${email}.`;
    confirmationMessage.innerHTML = message;

   
    confirmationMessage.style.display = 'block';
}
</script>
<div class="content">
    <div class="restaurant-details">
        <h1>Fauget Sushi</h1>
        <address>
            <div class="address">34C - 3237 boul des Sources, QC</div><br>
            <div class="phone">+1 (123) 555-5555</div>
        </address>
    </div>

    <div class="social-buttons">
        <a href="#" class="fb btn"><i class="fa fa-facebook fa-fw"></i> Login with Facebook</a>
        <a href="#" class="google btn"><i class="fa fa-google fa-fw"></i> Login with Google+</a>
    </div>

    <div class="container">

        <div id="confirmation-message"><?php echo $confirmationMessage;?></div>

    </div>
</div>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
    <label>Name:</label>
    <input type="text" name="name" id="name" value="<?php echo $name; ?>" required>
    <span class="error"><?php echo $nameError; ?></span><br>
    <label>Email:</label>
    <input type="text" name="email" id="email" value="<?php echo $email; ?>" required>
    <span class="error"><?php echo $emailError; ?></span><br>
    <label>Phone:</label>
    <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>" required>
    <span class="error"><?php echo $phoneError; ?></span><br>
     <div class="group">
              <label>Preferred communication channel:</label>
              <label class="email-radio-button checked ">
                <input aria-checked="true" required="" name="communication-channel" id="email" class="email-view" type="radio" value="email">
                <span>E-mail</span>
              </label>
              <label class="phone-radio-button  ">
                <input aria-checked="false" name="communication-channel" id="phone" class="phone-view" type="radio" value="sms">
                <span>Mobile</span>
              </label>
            </div>
    <label>Comment:</label>
    <textarea name="comment" rows="5" cols="40"><?php echo $comment; ?></textarea><br>

    <button type="submit" name="submit">Submit!</button>
</form>

</body>
</html>
