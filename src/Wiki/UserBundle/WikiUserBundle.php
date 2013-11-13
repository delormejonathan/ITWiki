<?php

namespace Wiki\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WikiUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}