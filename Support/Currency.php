<?php

namespace Modules\Icurrency\Support;

use Modules\Icurrency\Entities\Currency as CurrencyEntity;

class Currency
{

  /**
   * @var
   */
  protected $localeCurency;

  /**
   * Currency constructor.
   */
  public function __construct()
  {
    /* Init default Currency */
    $this->localeCurency = CurrencyEntity::defaultCurrency();
  }

  /**
   * @param $value
   * @return float
   */
  public function convert($value)
  {
    /*
     * calculate value,
     * from the default currency
     * to the local currency of the app
     */
    $result = floatval($value) / floatval(isset($this->localeCurency->value) && $this->localeCurency->value ? $this->localeCurency->value : 1);

    /* Return value in the follow formant 'xxxx.xx' */
    return  $this->trasformerResult($result);
  }

  /**
   * @param $value
   * @param $to
   * @param $from
   * @return float
   */
  public function convertFromTo($value, $to, $from = 'USD')
  {
    /* Convert value from currency "From" */
    $fromCurrency = CurrencyEntity::currencyCode($from);
    $fromCurrencyValue = intval($value) * ($fromCurrency->value ?? 1);

    /* Convert value from currency "to" */
    $toCurrency = CurrencyEntity::currencyCode($to);
    $toCurrencyValue = $this->trasformerResult($fromCurrencyValue) / ($toCurrency->value ?? 1);

    /* Return value in the follow formant 'xxxx.xx' */
    return  $this->trasformerResult($toCurrencyValue);
  }


  /**
   * @return mixed
   */
  public function getLocaleCurrency()
  {
    return $this->localeCurency;
  }

  /**
   * @param $localeCurency
   */
  public function setLocaleCurrency($newCurrency)
  {
    $this->localeCurency = CurrencyEntity::currencyCode($newCurrency);
  }

  /**
   * @param $result
   * @return float
   */
  private function trasformerResult ( $result ){
    return floatval(number_format($result, 2, '.', ''));
  }

  public function getSupportedCurrencies (){
    return CurrencyEntity::all();
  }

}
