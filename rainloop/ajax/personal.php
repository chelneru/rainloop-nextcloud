<?php

/**
 * Nextcloud - RainLoop mail plugin
 *
 * @author RainLoop Team, Nextgen-Networks (@nextgen-networks), Tab Fitts (@tabp0le), Pierre-Alain Bandinelli (@pierre-alain-b)
 *
 * Based initially on https://github.com/RainLoop/rainloop-webmail/tree/master/build/owncloud
 */

\OC_JSON::checkLoggedIn();
\OC_JSON::checkAppEnabled('rainloop');
\OC_JSON::callCheck();

$sEmail = '';
$sLogin = '';

if (isset($_POST['appname'], $_POST['rainloop-password'], $_POST['rainloop-email']) && 'rainloop' === $_POST['appname'])
{
	$sUser = OCP\User::getUser();

	$sPostEmail = $_POST['rainloop-email'];

	\OC::$server->getConfig()->setUserValue($sUser, 'rainloop', 'rainloop-email', $sPostEmail);

	$sPass = $_POST['rainloop-password'];
	
	if (\OC::$server->getConfig()->getAppValue('rainloop', 'rainloop-autologin-with-email', false)) {

        $pwd_file = OC_App::getAppPath('rainloop').'/app/data/AUTOLOGIN_PW';

        $sPass = file_get_contents($pwd_file);

    }
	if ('******' !== $sPass && '' !== $sPass)
	{
		include_once OC_App::getAppPath('rainloop').'/lib/RainLoopHelper.php';

		\OC::$server->getConfig()->setUserValue($sUser, 'rainloop', 'rainloop-password',
			OC_RainLoop_Helper::encodePassword($sPass, md5($sPostEmail)));
	}

	$sEmail = \OC::$server->getConfig()->getUserValue($sUser, 'rainloop', 'rainloop-email', '');
}
else
{
	sleep(1);
	OC_JSON::error(array('Message' => 'Invalid argument(s)', 'Email' => $sEmail));
	return false;
}

sleep(1);
\OC_JSON::success(array('Message' => 'Saved successfully', 'Email' => $sEmail));
return true;
