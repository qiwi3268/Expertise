<?php

final class TestTable{

   static public function getIdsFunctionalPurpose():array {

      $query = "SELECT `id`
                FROM `misc_functional_purpose`";

      return SimpleQuery::getSimpleArray($query);

   }

   static public function getAssocFunctionalPurposeSubsector():array {

      $query = "SELECT *
                FROM `misc_functional_purpose_subsector`";

      return SimpleQuery::getFetchAssoc($query);
   }

   static public function getAssocFunctionalPurposeGroup():array {
      $query = "SELECT *
                FROM `misc_functional_purpose_group`";

      return SimpleQuery::getFetchAssoc($query);
   }

   static public function createSubsectorForFunctionalPurpose(int $id_main, int $id_dependent):void {
      $query = "INSERT INTO `misc_functional_purpose_subsector_FOR_misc_functional_purpose`
                (`id_main`,`id_dependent`)
                VALUES (?,?)";
      ParametrizedQuery::set($query, [$id_main, $id_dependent]);
   }

   static public function createGroupsForSubSector(int $id_main, int $id_dependent):void {
      $query = "INSERT INTO `misc_functional_purpose_group_FOR_misc_functional_purpose_subsec`
                (`id_main`,`id_dependent`)
                VALUES (?,?)";
      ParametrizedQuery::set($query, [$id_main, $id_dependent]);
   }

    static public function getAssocFederalProject():array {
        $query = "SELECT *
                FROM `misc_federal_project`";

        return SimpleQuery::getFetchAssoc($query);
    }

    static public function createFederalProjectForNationalProject(int $id_main, int $id_dependent):void {
        $query = "INSERT INTO `misc_federal_project_FOR_national_project`
                (`id_main`,`id_dependent`)
                VALUES (?,?)";
        ParametrizedQuery::set($query, [$id_main, $id_dependent]);
    }
}