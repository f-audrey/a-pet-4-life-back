<?php
namespace App\Models;

class Data
{
private $data = [

    [
        "category_name" => "Associations",
        "picture" => "associations.png",
        "description" => "Pour ajouter, modifer ou supprimer des associations.",
        "path" => "{{ path('app_back_asso') }}",
    ],

    [
      "category_name" => "Particuliers",
      "picture" => "particuliers.png",
      "description" => "Pour ajouter, modifer ou supprimer les particuliers.",
      "path" => "{{ path('app_back_particular') }}",
  ],

  [
    "category_name" => "Espèces",
    "picture" => "espèces.png",
    "description" => "Pour ajouter, modifer ou supprimer les espèces.",
    "path" => "{{ path('app_back_particular') }}",
],
];

public function getAllData()
{
    return $this->data;
}
}