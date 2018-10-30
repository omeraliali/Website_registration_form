<?php
//signup.php
include 'connect.php';
include 'header.php';
 
echo '<h3>Sign up</h3>';

if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    /*the form hasn't been posted yet, display it
      note that the action="" will cause the form to post to the same page it is on */
    echo '<form method="post" action="">
        Username (Registraion Ref): <input type="text" name="mem_reg_ref" />
		<br>Firstname: <input type="text" name="mem_fname" />
		<br>Lastname: <input type="text" name="mem_lname" />
        <br>Password: <input type="password" name="mem_password">
        <br>Password again: <input type="password" name="mem_password_check">
        <br>E-mail: <input type="email" name="mem_email">
        <br>Security Question: <input type="text" name="mem_security_question">
        <br>Answer: <input type="text" name="mem_security_Q_answer">
        <br><input type="submit" value="Create user account" />
     </form>';
}
else
{
    /* so, the form has been posted, we'll process the data in three steps:
        1.  Check the data
        2.  Let the user refill the wrong fields (if necessary)
        3.  Save the data 
    */
    $errors = array(); // declare the array for later use 
     
    if(isset($_POST['mem_reg_ref']))
    {
        //the user name exists
        if(!ctype_alnum($_POST['mem_reg_ref']))
        {
            $errors[] = 'The username/registration Ref can only contain letters and digits.';
        }
        if(strlen($_POST['mem_reg_ref']) > 30)
        {
            $errors[] = 'The username/registration Ref cannot be longer than 30 characters.';
        }
    }
    else
    {
        $errors[] = 'The username field must not be empty.';
    }
     
     
    if(isset($_POST['mem_password']))
    {
        if($_POST['mem_password'] != $_POST['mem_password_check'])
        {
            $errors[] = 'The two passwords did not match.';
        }
    }
    else
    {
        $errors[] = 'The password field cannot be empty.';
    }
     
    if(!empty($errors)) //check for an empty array, if there are errors, they're in this array (note the ! operator)
    {
        echo 'STOP.. a couple of fields are not filled in correctly..';
        echo '<ul>';
        foreach($errors as $key => $value) // walk through the array so all the errors get displayed 
        {
            echo '<li>' . $value . '</li>'; // this generates a nice error list 
        }
        echo '</ul>';
    }
    else
    {
        //the form has been posted without, so save it
        //notice the use of mysql_real_escape_string, keep everything safe!
        //also notice the sha1 function which hashes the password
        $sql = "INSERT INTO
                    tbl_member(mem_reg_ref, mem_fname, mem_lname, mem_password, mem_email,
					mem_security_question, mem_security_Q_answer, mem_reg_date, mem_role)
                VALUES('" . mysqli_real_escape_string($_SESSION['Cn'],$_POST['mem_reg_ref']) . "',
						'" . mysqli_real_escape_string($_SESSION['Cn'],$_POST['mem_fname']) . "',
						'" . mysqli_real_escape_string($_SESSION['Cn'],$_POST['mem_lname']) . "',
						'" . sha1($_POST['mem_password']) . "',
						'" . mysqli_real_escape_string($_SESSION['Cn'],$_POST['mem_email']) . "',
						'" . mysqli_real_escape_string($_SESSION['Cn'],$_POST['mem_security_question']) . "',
						'" . mysqli_real_escape_string($_SESSION['Cn'],$_POST['mem_security_Q_answer']) . "',
                        NOW(),
                        255)";
                         
        $result = mysqli_query($_SESSION['Cn'],$sql);
        if(!$result)
        {
            //something went wrong, display the error
            echo 'Something went wrong while registering. Please try again later.';
            //echo mysql_error(); //debugging purposes, uncomment when needed
        }
        else
        {
            echo 'Successfully registered. You can now <a href="signin.php">sign in</a> and start posting! :-)';
        }
    }
}

include 'footer.php';
?>
