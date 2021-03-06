<?php

require_once 'PEAR/PackageFileManager2.php';

$version = '2.0.0';

$notes = <<<EOT
* Suppress and handle bad cookies
EOT;

$description =
	"Blorg, or better yet Blörg is our awesome new blogging package. Blörgy ".
	"is a site built using the Blörg package that supports multiple blogs. ".
	"Currently, several weblogs and other sites are using Blörg.";

$package = new PEAR_PackageFileManager2();
PEAR::setErrorHandling(PEAR_ERROR_DIE);

$result = $package->setOptions(
	array(
		'filelistgenerator' => 'svn',
		'simpleoutput'      => true,
		'baseinstalldir'    => '/',
		'packagedirectory'  => './',
		'dir_roles'         => array(
			'Blorg'         => 'php',
			'locale'        => 'data',
			'dependencies'  => 'data',
			'www'           => 'data',
		),
	)
);

$package->setPackage('Blorg');
$package->setSummary('A package for creating weblogs.');

$package->setDescription($description);
$package->setChannel('pear.silverorange.com');
$package->setPackageType('php');
$package->setLicense('private', 'http://www.silverorange.com/');

$package->setReleaseVersion($version);
$package->setReleaseStability('stable');
$package->setAPIVersion('0.0.1');
$package->setAPIStability('stable');
$package->setNotes($notes);

$package->addIgnore('package.php');

$package->addMaintainer('lead', 'gauthierm', 'Mike Gauthier', 'mike@silverorange.com');

$package->addReplacement('Blorg/Blorg.php', 'pear-config', '@DATA-DIR@', 'data_dir');

$package->setPhpDep('5.2.4');
$package->setPearinstallerDep('1.4.0');
$package->addPackageDepWithChannel('required', 'Swat', 'pear.silverorange.com', '1.4.131');
$package->addPackageDepWithChannel('required', 'Site', 'pear.silverorange.com', '1.6.18');
$package->addPackageDepWithChannel('required', 'Admin', 'pear.silverorange.com', '1.3.42');
$package->addPackageDepWithChannel('required', 'XML_Atom', 'pear.silverorange.com', '0.1.7');
$package->addPackageDepWithChannel('optional', 'Services_Akismet2', 'pear.php.net', '0.2.0');
$package->addPackageDepWithChannel('optional', 'Services_Twitter', 'pear.php.net', '0.5.1');
$package->generateContents();

if (isset($_GET['make']) || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
	$package->writePackageFile();
} else {
	$package->debugPackageFile();
}

?>
