<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 * @category Piwik
 * @package Piwik
 */

namespace Piwik\Translate\Filter;

use Piwik\Translate\Filter\FilterAbstract;

/**
 * @package Piwik
 * @subpackage Piwik_Translate
 */
class UnnecassaryWhitespaces extends FilterAbstract
{
    /**
     * Filter the given translations
     *
     * @param array $translations
     *
     * @return array   filtered translations
     *
     */
    public function filter($translations)
    {
        foreach ($translations AS $pluginName => $pluginTranslations) {
            foreach ($pluginTranslations AS $key => $translation) {

                $baseTranslation  = $this->_baseTranslations[$pluginName][$key];

                // remove excessive line breaks (and leading/trailing whitespace) from translations
                $stringNoLineBreak = trim($translation);
                $stringNoLineBreak = str_replace("\r", "", $stringNoLineBreak); # remove useless carrige renturns
                $stringNoLineBreak = preg_replace('/(\n[ ]+)/', "\n", $stringNoLineBreak); # remove excessive white spaces
                $stringNoLineBreak = preg_replace('/([\n]{2,})/', "\n\n", $stringNoLineBreak); # remove excessive line breaks
                if (!isset($baseTranslation) || !substr_count($baseTranslation, "\n")) {
                    $stringNoLineBreak = preg_replace("/[\n]+/", " ", $stringNoLineBreak); # remove all line breaks if english string doesn't contain any
                }
                $stringNoLineBreak = preg_replace('/([ ]{2,})/', " ", $stringNoLineBreak); # remove excessive white spaces again as there might be there now, after removing line breaks
                if ($translation !== $stringNoLineBreak) {
                    $this->_filteredData[$pluginName][$key] = $translation;
                    $translations[$pluginName][$key] = $stringNoLineBreak;
                    continue;
                }
            }
        }

        return $translations;
    }
}