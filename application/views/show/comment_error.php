
<?php

if (isset($message))
{
	$open_tag = '<div id="comment_error"> <a name="error" class="text">';
	$close_tag = '</a></div>';

	switch ($message) 
	{
		case 'noauthor':
			echo "$open_tag &Egrave; necessario 
						inserire un nome per pubblicare 
						un commento $close_tag";
			break;
		case 'nobody':
			echo "$open_tag Inserisci un commento
						prima di pubblicarlo! $close_tag";
			break;
		case 'toolong':
			echo "$open_tag La Divina Commedia &egrave;
						gi&agrave; stata pubblicata!
						Per favore fai una cosa un po'
						pi&ugrave; breve $close_tag";
			break;
		case 'recaptcha':
			echo "$open_tag Hai sbagliato a digitare
						il codice captcha $close_tag";
		default:
			break;
	}
}
?>
