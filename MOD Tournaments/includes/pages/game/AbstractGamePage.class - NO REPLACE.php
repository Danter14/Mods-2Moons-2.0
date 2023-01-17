<?php

/**
 * @mods Tournoi
 * @version 1.0
 * @author yamilrh
 * @modification Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

abstract class AbstractGamePage
{
	protected function getPageData()
	{
		$this->assign(array(
			'tourneyEnd'		=> $config->tourneyEnd - TIMESTAMP, // Addon tournoiment
		));
	}
}