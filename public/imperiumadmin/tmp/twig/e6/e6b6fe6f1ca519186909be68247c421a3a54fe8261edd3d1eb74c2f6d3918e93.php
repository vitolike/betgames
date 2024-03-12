<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* database/search/main.twig */
class __TwigTemplate_72f9ac41c0ba0758034e38d70c3510832ce9e112b54013c5538ce08c79baeb6d extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<a id=\"db_search\"></a>
<form id=\"db_search_form\" method=\"post\" action=\"";
        // line 2
        echo PhpMyAdmin\Url::getFromRoute("/database/search");
        echo "\" name=\"db_search\" class=\"ajax lock-page\">
    ";
        // line 3
        echo PhpMyAdmin\Url::getHiddenInputs(($context["db"] ?? null));
        echo "
    <fieldset class=\"pma-fieldset\">
        <legend>";
echo _gettext("Search in database");
        // line 5
        echo "</legend>
        <p>
            <label for=\"criteriaSearchString\" class=\"d-block\">
                ";
echo _gettext("Words or values to search for (wildcard: \"%\"):");
        // line 9
        echo "            </label>
            <input id=\"criteriaSearchString\" name=\"criteriaSearchString\" class=\"w-75\" type=\"text\" value=\"";
        // line 11
        echo twig_escape_filter($this->env, ($context["criteria_search_string"] ?? null), "html", null, true);
        echo "\">
        </p>

        <fieldset class=\"pma-fieldset\">
            <legend>";
echo _gettext("Find:");
        // line 15
        echo "</legend>

            <div>
              <input type=\"radio\" name=\"criteriaSearchType\" id=\"criteriaSearchTypeRadio1\" value=\"1\"";
        // line 18
        echo (((0 === twig_compare(($context["criteria_search_type"] ?? null), "1"))) ? (" checked") : (""));
        echo ">
              <label for=\"criteriaSearchTypeRadio1\">";
echo _gettext("at least one of the words");
        // line 19
        echo " ";
        echo PhpMyAdmin\Html\Generator::showHint(_gettext("Words are separated by a space character (\" \")."));
        echo "</label>
            </div>
            <div>
              <input type=\"radio\" name=\"criteriaSearchType\" id=\"criteriaSearchTypeRadio2\" value=\"2\"";
        // line 22
        echo (((0 === twig_compare(($context["criteria_search_type"] ?? null), "2"))) ? (" checked") : (""));
        echo ">
              <label for=\"criteriaSearchTypeRadio2\">";
echo _gettext("all of the words");
        // line 23
        echo " ";
        echo PhpMyAdmin\Html\Generator::showHint(_gettext("Words are separated by a space character (\" \")."));
        echo "</label>
            </div>
            <div>
              <input type=\"radio\" name=\"criteriaSearchType\" id=\"criteriaSearchTypeRadio3\" value=\"3\"";
        // line 26
        echo (((0 === twig_compare(($context["criteria_search_type"] ?? null), "3"))) ? (" checked") : (""));
        echo ">
              <label for=\"criteriaSearchTypeRadio3\">";
echo _gettext("the exact phrase as substring");
        // line 27
        echo "</label>
            </div>
            <div>
              <input type=\"radio\" name=\"criteriaSearchType\" id=\"criteriaSearchTypeRadio4\" value=\"4\"";
        // line 30
        echo (((0 === twig_compare(($context["criteria_search_type"] ?? null), "4"))) ? (" checked") : (""));
        echo ">
              <label for=\"criteriaSearchTypeRadio4\">";
echo _gettext("the exact phrase as whole field");
        // line 31
        echo "</label>
            </div>
            <div>
              <input type=\"radio\" name=\"criteriaSearchType\" id=\"criteriaSearchTypeRadio5\" value=\"5\"";
        // line 34
        echo (((0 === twig_compare(($context["criteria_search_type"] ?? null), "5"))) ? (" checked") : (""));
        echo ">
              <label for=\"criteriaSearchTypeRadio5\">";
echo _gettext("as regular expression");
        // line 35
        echo " ";
        echo PhpMyAdmin\Html\MySQLDocumentation::show("Regexp");
        echo "</label>
            </div>
        </fieldset>

        <fieldset class=\"pma-fieldset\">
            <legend>";
echo _gettext("Inside tables:");
        // line 40
        echo "</legend>
            <p>
                <a href=\"#\" id=\"select_all\">
                    ";
echo _gettext("Select all");
        // line 44
        echo "                </a> /
                <a href=\"#\" id=\"unselect_all\">
                    ";
echo _gettext("Unselect all");
        // line 47
        echo "                </a>
            </p>
            <select class=\"resize-vertical\" id=\"criteriaTables\" name=\"criteriaTables[]\" multiple>
                ";
        // line 50
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["tables_names_only"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["each_table"]) {
            // line 51
            echo "                    <option value=\"";
            echo twig_escape_filter($this->env, $context["each_table"], "html", null, true);
            echo "\"
                            ";
            // line 52
            if ((1 === twig_compare(twig_length_filter($this->env, ($context["criteria_tables"] ?? null)), 0))) {
                // line 53
                echo ((twig_in_filter($context["each_table"], ($context["criteria_tables"] ?? null))) ? (" selected") : (""));
                echo "
                            ";
            } else {
                // line 55
                echo " selected";
                echo "
                            ";
            }
            // line 57
            echo "                        >
                        ";
            // line 58
            echo twig_escape_filter($this->env, $context["each_table"], "html", null, true);
            echo "
                    </option>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['each_table'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 61
        echo "            </select>
        </fieldset>

        <p>
            ";
        // line 66
        echo "            <label for=\"criteriaColumnName\" class=\"d-block\">
                ";
echo _gettext("Inside column:");
        // line 68
        echo "            </label>
            <input id=\"criteriaColumnName\" type=\"text\" name=\"criteriaColumnName\" class=\"w-75\" value=\"";
        // line 70
        (( !twig_test_empty(($context["criteria_column_name"] ?? null))) ? (print (twig_escape_filter($this->env, ($context["criteria_column_name"] ?? null), "html", null, true))) : (print ("")));
        echo "\">
        </p>
    </fieldset>
    <fieldset class=\"pma-fieldset tblFooters\">
        <input id=\"buttonGo\" class=\"btn btn-primary\" type=\"submit\" name=\"submit_search\" value=\"";
echo _gettext("Go");
        // line 74
        echo "\">
    </fieldset>
</form>
<div id=\"togglesearchformdiv\">
    <a id=\"togglesearchformlink\"></a>
</div>
<div id=\"searchresults\"></div>
<div id=\"togglesearchresultsdiv\"><a id=\"togglesearchresultlink\"></a></div>
<br class=\"clearfloat\">
";
        // line 84
        echo "<div id=\"table-info\">
    <a id=\"table-link\" class=\"item\"></a>
</div>
";
        // line 88
        echo "<div id=\"browse-results\">
    ";
        // line 90
        echo "</div>
<div id=\"sqlqueryform\" class=\"clearfloat\">
    ";
        // line 93
        echo "</div>
";
        // line 95
        echo "<button class=\"btn btn-secondary\" id=\"togglequerybox\"></button>
";
    }

    public function getTemplateName()
    {
        return "database/search/main.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  227 => 95,  224 => 93,  220 => 90,  217 => 88,  212 => 84,  201 => 74,  193 => 70,  190 => 68,  186 => 66,  180 => 61,  171 => 58,  168 => 57,  163 => 55,  158 => 53,  156 => 52,  151 => 51,  147 => 50,  142 => 47,  137 => 44,  131 => 40,  121 => 35,  116 => 34,  111 => 31,  106 => 30,  101 => 27,  96 => 26,  89 => 23,  84 => 22,  77 => 19,  72 => 18,  67 => 15,  59 => 11,  56 => 9,  50 => 5,  44 => 3,  40 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/search/main.twig", "/var/www/html/public/imperiumadmin/templates/database/search/main.twig");
    }
}
