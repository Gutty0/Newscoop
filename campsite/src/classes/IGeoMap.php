<?php
/**
 * @package Campsite
 *
 * @author Petr Jasek <petr.jasek@sourcefabric.org>
 * @copyright 2010 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl.txt
 * @link http://www.sourcefabric.org
 */

/**
 * Geolocations Map interace
 */
interface IGeoMap
{
    /**
     * Get map locations
     * @return array of IGeoMapLocation
     */
    public function getLocations();
}
