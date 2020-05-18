<?php

namespace Kernel;

use Symfony\Component\Finder\Finder;
use RuntimeException;

class Config
{
	/**
	 * Displays the Application Configuration value which are stored
	 * in the '\config' directory - each part represents an array
	 * dimension
	 *
	 * @param string $string containing "." seperators
	 * @return string
	 */
	public static function get($string)
	{
		$config = self::getConfigs();

		$token  = strtok($string, '.');

		while ($token  !== false) {

			if (!isset($config[$token])) {
				return null;
			}

			$config = $config[$token];
			$token  = strtok('.');
		}

		return $config;
	}

	/**
	 * Loads All application and enviromment configuration inforamtion
	 * in the form of key => value pairs. This function looks though
	 * the '\cnfig' directory for config files and adds them to the
	 * Global App array. Each file forms its own assosiative array.     *
	 *
	 * @param  void
	 * @return array $config  Associative array
	 */
	private static function getConfigs()
	{
		$finder = new Finder();
		$finder->files()->in(dirname(__DIR__) . '/config');

		if ($finder->hasResults()) {

			foreach ($finder as $file) {
				$absoluteFilePath = $file->getRealPath();
				$fileNameWithExtension = $file->getRelativePathname();
				$filename = preg_replace('/.[^.]*$/', '', $fileNameWithExtension);
				$config[$filename] = include $absoluteFilePath;
			}
		}
		return $config;
	}
}