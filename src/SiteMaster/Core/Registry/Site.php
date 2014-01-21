<?php
namespace SiteMaster\Core\Registry;

use DB\Record;

class Site extends Record
{
    public $id;               //int required
    public $base_url;         //varchar required
    public $title;            //varchar
    public $support_email;    //varchar

    public function keys()
    {
        return array('id');
    }

    public static function getTable()
    {
        return 'sites';
    }

    /**
     * Get a site by its base url
     * 
     * @param $base_url
     * @return bool|Site
     */
    public static function getByBaseURL($base_url)
    {
        return self::getByAnyField(__CLASS__, 'base_url', $base_url);
    }

    /**
     * Create a new site
     * 
     * @param $base_url
     * @param array $details
     * @return bool|Site
     */
    public static function createNewSite($base_url, array $details = array())
    {
        $site = new self();
        $site->synchronizeWithArray($details);
        $site->base_url = $base_url;
        
        if (!$site->insert()) {
            return false;
        }
        
        return $site;
    }

    /**
     * Get the approved members of this site
     * 
     * @return Site\Members\Approved
     */
    public function getMembers()
    {
        return new Site\Members\Approved(array('site_id' => $this->id));
    }

    /**
     * Get the closest parent site
     * 
     * @return bool|Site
     */
    public function getParentSite()
    {
        $query = $this->base_url;
        
        //All base URLs must end in a /, so trim it off
        $query = rtrim($query, "/");
        
        $registry = new Registry();
        
        $site = $registry->getClosestSite($query);

        /**
         * It might be the case that the base urls are the same.
         * This is because Registry::getClosestSite('http://domain.com') returns http://domain.com/
         */
        if ($site->base_url == $this->base_url) {
            return false;
        }
        
        return $site;
    }

    /**
     * Get the title of the site.  The title is the base_url, unless the title field is not null
     * 
     * @return string
     */
    function getTitle()
    {
        if ($this->title) {
            return $this->title;
        }
        
        return $this->base_url;
    }
}