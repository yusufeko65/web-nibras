<?php
/**
 * This code is licensed under AGPLv3 license or Afterlogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\Contacts\Classes;

/**
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing Afterlogic Software License
 * @copyright Copyright (c) 2019, Afterlogic Corp.
 *
 * @package Classes
 * @subpackage GroupContact
 * 
 * @property string $GroupUUID
 * @property string $ContactUUID
 */
class GroupEvent extends \Aurora\System\EAV\Entity
{
	protected $aStaticMap = [
		'GroupUUID'	=> ['string', '', true],
		'CalendarUUID'	=> ['string', '', true],
		'EventUUID'	=> ['string', '', true],
	];
}
