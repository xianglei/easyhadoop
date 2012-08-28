<?php
class Etc 
{
	public function CheckFileExists($pFilename)
	{
		if(!file_exists($pFilename))
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}
?>