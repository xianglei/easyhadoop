<?php

include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/user_admin_sidebar.html";

if(!$_GET['action'])
{
	echo "<script>this.location='./UserAdmin.php?action=ChangePassword'</script>";
}
elseif($_GET['action'] == "ChangePassword")
{
	echo '<div class=span10>';
	
	if(!$_POST['cur_pass'] || !$_POST['new_pass'])
	{
		include_once "templates/change_password.html";		
	}
	else
	{
		echo "<h3>";
		
		if($auth->AuthUser($_SESSION['username'],$_POST['cur_pass']))
		{
			if($_POST['cur_pass'] == $_POST['new_pass1'])
			{
				echo $lang['passwordEqual'];
			}
			else
			{
				if($_POST['new_pass1'] != $_POST['new_pass2'])
				{
					echo $lang['passwordNotEqual'];
				}
				else
				{
					$username = $_SESSION['username'];
					$password = $_POST['password'];
					$newpassword = $_POST['new_pass1'];
					if($auth->ChangePassword($username, $password, $newpassword))
					{
						echo $lang['changePasswordSuccess'];
					}
					else
					{
						echo $lang['changePasswordFailed'];
					}
				}
			}
		}
		else
		{
			echo $lang['notValidPassword'];
		}
		echo "</h3>";
	}
	
	echo '</div>';
}

include_once "templates/footer.html";
?>