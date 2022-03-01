<?php

namespace App\Models;

class Search
{
  private $geolocation;
  private $responseLocation;
  private $species;

  /**
   * Get the value of geolocation
   */ 
  public function getGeolocation()
  {
    return $this->geolocation;
  }

  /**
   * Set the value of geolocation
   *
   * @return  self
   */ 
  public function setGeolocation($geolocation)
  {
    $this->geolocation = $geolocation;

    return $this;
  }

  /**
   * Get the value of responseLocation
   */ 
  public function getResponseLocation()
  {
    return $this->responseLocation;
  }

  /**
   * Set the value of responseLocation
   *
   * @return  self
   */ 
  public function setResponseLocation($responseLocation)
  {
    $this->responseLocation = $responseLocation;

    return $this;
  }

  /**
   * Get the value of species
   */ 
  public function getSpecies()
  {
    return $this->species;
  }

  /**
   * Set the value of species
   *
   * @return  self
   */ 
  public function setSpecies($species)
  {
    $this->species = $species;

    return $this;
  }
}