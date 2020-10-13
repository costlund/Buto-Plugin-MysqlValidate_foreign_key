<?php
class PluginMysqlValidate_foreign_key{
  private $settings;
  private $mysql;
  private $i18n = null;
  function __construct() {
    wfPlugin::includeonce('wf/yml');
    wfPlugin::includeonce('wf/array');
    wfPlugin::includeonce('wf/mysql');
    $this->mysql = new PluginWfMysql();
    $this->settings = wfPlugin::getPluginSettings('mysql/validate_foreign_key', true);
    $this->settings->set('data/mysql', wfSettings::getSettingsFromYmlString($this->settings->get('data/mysql')));
    wfPlugin::includeonce('i18n/translate_v1');
    $this->i18n = new PluginI18nTranslate_v1();
    $this->i18n->setPath('/plugin/mysql/validate_foreign_key/i18n');
  }
  private function db_open(){
    $this->mysql->open($this->settings->get('data/mysql'));
  }
  private function getSql($key){
    return new PluginWfYml(__DIR__.'/mysql/sql.yml', $key);
  }
  public function validate($field, $form, $data = array()){
    /**
     * 
     */
    $form = new PluginWfArray($form);
    $data = new PluginWfArray($data);
    $result = new PluginWfArray();
    /**
     * 
     */
    if($form->get("items/".$field."/is_valid")){
      $this->db_open();
      /**
       * 
       */
      $sql = $this->getSql('db_get_fk');
      $sql->setByTag($this->settings->get('data/mysql'));
      $sql->setByTag($data->get());
      $this->mysql->execute($sql->get());
      $rs = $this->mysql->getMany();
      foreach($rs as $k => $v){
        $sql = $this->getSql('db_get_table');
        foreach($v as $k2 => $v2){
          $sql->set('sql', str_replace("[$k2]", $v2, $sql->get('sql')));
        }
        $sql->setByTag($form->get("items/".$field.""));
        $rs[$k]['sql'] = $sql->get();
        $this->mysql->execute($sql->get());
        $rs2 = $this->mysql->getOne();
        $rs[$k]['result'] = $rs2->get('count_value');
      }
      /**
       * 
       */
      $result->set('foreign_keys', $rs);
      /**
       * Sum all.
       */
      $sum = 0;
      foreach($result->get('foreign_keys') as $v){
        $sum += $v['result'];
      }
      $result->set('sum', $sum);
      /**
       * 
       */
      if($result->get('sum')){
        /**
         * 
         */
        $form->set("items/$field/is_valid", false);
        $form->set("items/$field/errors/", $this->i18n->translateFromTheme('?label has reference data and can not be removed (?sum posts).', array('?label' => $form->get("items/$field/label"), '?sum' => $result->get('sum'))));
      }
    }
    return $form->get();
  }
}
