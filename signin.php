<?php
//signin.php
include 'connect.php';
include 'header.php';
 
echo '<h3>Sign in</h3>';
 
//first, check if the user is already signed in. If that is the case, there is no need to display this page
if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
{
    echo 'You are already signed in, you can <a href="signout.php">sign out</a> if you want.';
}
else
{
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        /*the form hasn't been posted yet, display it
          note that the action="" will cause the form to post to the same page it is on */
        echo '<form method="post" action="">
            Username (Registration Ref): <input type="text" name="mem_reg_ref" />
            Password: <input type="password" name="mem_password">
            <input type="submit" value="Sign in" />
         </form>';
    }
    else
    {
        /* so, the form has been posted, we'll process the data in three steps:
            1.  Check the data
            2.  Let the user refill the wrong fields (if necessary)
            3.  Varify if the data is correct and return the correct response
        */
        $errors = array(); /* declare the array for later use */
         
        if(!isset($_POST['mem_reg_ref']))
        {
            $errors[] = 'The username (Registration Ref) field must not be empty.';
        }
         
        if(!isset($_POST['mem_password']))
        {
            $errors[] = 'The password field must not be empty.';
        }

        if(!empty($errors)) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/
        {
            echo 'Uh-oh.. a couple of fields are not filled in correctly..';
            echo '<ul>';
            foreach($errors as $key => $value) /* walk through the array so all the errors get displayed */
            {
                echo '<li>' . $value . '</li>'; /* this generates a nice error list */
            }
            echo '</ul>';
        }
        else
        {
            //the form has been posted without errors, so save it
            //notice the use of mysql_real_escape_string, keep everything safe!
            //also notice the sha1 function which hashes the password
            //mem_password = '" . sha1($_POST['mem_password']) . "'";
			$sql = "SELECT 
                        member_id,
                        mem_reg_ref,
						mem_fname,
                        mem_role
                    FROM
                        tbl_member
                    WHERE
                        mem_reg_ref = '" . mysqli_real_escape_string($_SESSION['Cn'],$_POST['mem_reg_ref']) . "'
                    AND
                        mem_password = '" . sha1($_POST['mem_password']) . "'";
            $result = mysqli_query($_SESSION['Cn'],$sql);
            if(!$result)
            {
                //something went wrong, display the error
                echo 'Something went wrong while signing in. Please try again later.';
                //echo mysql_error(); //debugging purposes, uncomment when needed
            }
            else
            {
                //the query was successfully executed, there are 2 possibilities
                //1. the query returned data, the user can be signed in
                //2. the query returned an empty result set, the credentials were wrong
                if(mysqli_affected_rows($_SESSION['Cn'])== 0)
                {
					echo 'You have supplied a wrong membership/password combination. Please try again.';
                }
                else
                {
                    //set the $_SESSION['signed_in'] variable to TRUE so that we can use it in other pages
                    $_SESSION['signed_in'] = true;
                     
                    //we also put the user_id and user_name values in the $_SESSION, so we can use it at various pages
                    while($row = mysqli_fetch_assoc($result))
                    {
                        $_SESSION['member_id']    = $row['member_id'];
                        $_SESSION['mem_reg_ref']  = $row['mem_reg_ref'];
                        $_SESSION['mem_fname'] = $row['mem_fname'];
						$_SESSION['mem_role'] = $row['mem_role'];
                    }
                     
                    echo 'Welcome, ' . $_SESSION['mem_fname'] . '. <a href="index.php">Proceed to the forum overview</a>.';
                }
            }
        }
    }
}
 
include 'footer.php';
?>
