<?php


class PluginWhitelabelProfile extends CommonDBTM {
      
   static function canCreate() {

      if (isset($_SESSION['profile'])) {
        return ($_SESSION['profile']['whitelabel'] == 'w');
      }
      return false;
   }

   static function canView() {

      if (isset($_SESSION['profile'])) {
        return ($_SESSION['profile']['whitelabel'] == 'w'
                || $_SESSION['profile']['whitelabel'] == 'r');
      }
      return false;
   }

   static function createAdminAccess($ID) {

      $myProf = new self();
      // Only create profile if it's new
      if (!$myProf->getFromDB($ID)) {
      // Add entry to permissions database giving the user write privileges
         $myProf->add(array('id' => $ID,
                            'right'       => 'w'));
      }
   }

   static function addDefaultProfileInfos($profiles_id, $rights) {
      $profileRight = new ProfileRight();
      foreach ($rights as $right => $value) {
         if (!countElementsInTable('glpi_profilerights',
                                   ['profiles_id' => $profiles_id, 'name' => $right])) {
            $myright['profiles_id'] = $profiles_id;
            $myright['name']        = $right;
            $myright['rights']      = $value;
            $profileRight->add($myright);
            //Add right to the current session
            $_SESSION['glpiactiveprofile'][$right] = $value;
         }
      }
   }

   static function changeProfile() {

      $prof = new self();
      if ($prof->getFromDB($_SESSION['glpiactiveprofile']['id'])) {
         $_SESSION["glpi_plugin_whitelabel_profile"] = $prof->fields;
      } else {
         unset($_SESSION["glpi_plugin_whitelabel_profile"]);
      }
   }

   static function getRightsGeneral()
   {
      $rights = [
          ['itemtype'  => 'PluginWhitelabelProfile',
                'label'     => __('Use whitelabel', 'whitelabel'),
                'field'     => 'plugin_whitelabel_whitelabel',
                'rights'    =>  [UPDATE    => __('Allow editing', 'whitelabel')],
                'default'   => 23]];
      return $rights;
   }

   function showForm($profiles_id = 0, $openform = true, $closeform = true) {
      global $DB, $CFG_GLPI;
      

      if (!Session::haveRight("profile",READ)) {
         return false;
      }
      
      echo "<div class='firstbloc'>";
      if (($canedit = Session::haveRight('profile', UPDATE))
          && $openform) {
         $profile = new Profile();
        
         echo "<form method='post' action='".$profile->getFormURL()."'>";
      }
    
      $profile = new Profile();
      $profile->getFromDB($profiles_id);
      $rights = $this->getRightsGeneral();
      $profile->displayRightsChoiceMatrix($rights, ['default_class' => 'tab_bg_2',
                                                         'title'         => __('General')]);

      if ($canedit && $closeform) {
         echo "<div class='center'>";
         echo Html::hidden('id', ['value' => $profiles_id]);
         echo Html::submit(_sx('button', 'Save'), ['name' => 'update']);
         echo "</div>\n";
         Html::closeForm();
      }
      echo "</div>";
   }
}