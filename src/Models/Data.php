<?php
namespace App\Models;

class Data
{
private $data = [

    [
        "category_name" => "Associations",
        "picture" => "associations.png",
        "description" => "Pour ajouter, modifer ou supprimer des associations.",
        "path" => "/back/user/associations",
    ],

    [
      "category_name" => "Particuliers",
      "picture" => "particuliers.png",
      "description" => "Pour ajouter, modifer ou supprimer les particuliers.",
      "path" => "/back/user/particuliers",
  ],

  [
    "category_name" => "Espèces",
    "picture" => "espèces.png",
    "description" => "Pour ajouter, modifer ou supprimer les espèces.",
    "path" => "/back/species",
],
];

public function getAllData()
{
    return $this->data;
}
}