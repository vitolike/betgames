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

/* database/qbe/selection_form.twig */
class __TwigTemplate_09ea4a43f67a2a64e9dd562e0b7defd29eca034207a522beee8458894af42655 extends Template
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
        echo "<form action=\"";
        echo PhpMyAdmin\Url::getFromRoute("/database/qbe");
        echo "\" method=\"post\" id=\"formQBE\" class=\"lock-page\">
  ";
        // line 2
        echo PhpMyAdmin\Url::getHiddenInputs(($context["url_params"] ?? null));
        echo "

  <div class=\"w-100\">
    <fieldset class=\"pma-fieldset\">

      ";
        // line 7
        echo ($context["saved_searches_field"] ?? null);
        echo "

      <div class=\"table-responsive jsresponsive\">
        <table class=\"table table-borderless table-sm w-auto\">
          <tr class=\"noclick\">
            <th>";
echo _gettext("Column:");
        // line 12
        echo "</th>
            ";
        // line 13
        echo ($context["column_names_row"] ?? null);
        echo "
          </tr>

          <tr class=\"noclick\">
            <th>";
echo _gettext("Alias:");
        // line 17
        echo "</th>
            ";
        // line 18
        echo ($context["column_alias_row"] ?? null);
        echo "
          </tr>

          <tr class=\"noclick\">
            <th>";
echo _gettext("Show:");
        // line 22
        echo "</th>
            ";
        // line 23
        echo ($context["show_row"] ?? null);
        echo "
          </tr>

          <tr class=\"noclick\">
            <th>";
echo _gettext("Sort:");
        // line 27
        echo "</th>
            ";
        // line 28
        echo ($context["sort_row"] ?? null);
        echo "
          </tr>

          <tr class=\"noclick\">
            <th>";
echo _gettext("Sort order:");
        // line 32
        echo "</th>
            ";
        // line 33
        echo ($context["sort_order"] ?? null);
        echo "
          </tr>

          <tr class=\"noclick\">
            <th>";
echo _gettext("Criteria:");
        // line 37
        echo "</th>
            ";
        // line 38
        echo ($context["criteria_input_box_row"] ?? null);
        echo "
          </tr>

          ";
        // line 41
        echo ($context["ins_del_and_or_criteria_rows"] ?? null);
        echo "

          <tr class=\"noclick\">
            <th>";
echo _gettext("Modify:");
        // line 44
        echo "</th>
            ";
        // line 45
        echo ($context["modify_columns_row"] ?? null);
        echo "
          </tr>
        </table>
      </div>
    </fieldset>
  </div>

  <fieldset class=\"pma-fieldset tblFooters\">
    <div class=\"float-start\">
      <label for=\"criteriaRowAddSelect\">";
echo _gettext("Add/Delete criteria rows:");
        // line 54
        echo "</label>
      <select size=\"1\" name=\"criteriaRowAdd\" id=\"criteriaRowAddSelect\">
        <option value=\"-3\">-3</option>
        <option value=\"-2\">-2</option>
        <option value=\"-1\">-1</option>
        <option value=\"0\" selected>0</option>
        <option value=\"1\">1</option>
        <option value=\"2\">2</option>
        <option value=\"3\">3</option>
      </select>
    </div>

    <div class=\"float-start\">
      <label for=\"criteriaColumnAddSelect\">";
echo _gettext("Add/Delete columns:");
        // line 67
        echo "</label>
      <select size=\"1\" name=\"criteriaColumnAdd\" id=\"criteriaColumnAddSelect\">
        <option value=\"-3\">-3</option>
        <option value=\"-2\">-2</option>
        <option value=\"-1\">-1</option>
        <option value=\"0\" selected>0</option>
        <option value=\"1\">1</option>
        <option value=\"2\">2</option>
        <option value=\"3\">3</option>
      </select>
    </div>

    <div class=\"float-start\">
      <input class=\"btn btn-secondary\" type=\"submit\" name=\"modify\" value=\"";
echo _gettext("Update query");
        // line 80
        echo "\">
    </div>
  </fieldset>

  <div class=\"float-start w-100\">
    <fieldset class=\"pma-fieldset\">
      <legend>";
echo _gettext("Use tables");
        // line 86
        echo "</legend>

      <select class=\"resize-vertical\" name=\"TableList[]\" id=\"listTable\" size=\"";
        // line 88
        echo (((1 === twig_compare(twig_length_filter($this->env, ($context["criteria_tables"] ?? null)), 30))) ? ("15") : ("7"));
        echo "\" aria-label=\"";
echo _gettext("Use tables");
        echo "\" multiple>
        ";
        // line 89
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["criteria_tables"] ?? null));
        foreach ($context['_seq'] as $context["table"] => $context["selected"]) {
            // line 90
            echo "          <option value=\"";
            echo twig_escape_filter($this->env, $context["table"], "html", null, true);
            echo "\"";
            echo $context["selected"];
            echo ">";
            echo twig_escape_filter($this->env, $context["table"], "html", null, true);
            echo "</option>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['table'], $context['selected'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 92
        echo "      </select>
    </fieldset>

    <fieldset class=\"pma-fieldset tblFooters\">
      <input class=\"btn btn-secondary\" type=\"submit\" name=\"modify\" value=\"";
echo _gettext("Update query");
        // line 96
        echo "\">
    </fieldset>
  </div>
</form>

<form action=\"";
        // line 101
        echo PhpMyAdmin\Url::getFromRoute("/database/qbe");
        echo "\" method=\"post\" class=\"lock-page\">
  ";
        // line 102
        echo PhpMyAdmin\Url::getHiddenInputs(($context["db"] ?? null));
        echo "
  <input type=\"hidden\" name=\"submit_sql\" value=\"1\">

  <div class=\"float-start w-50\">
    <fieldset class=\"pma-fieldset\" id=\"tblQbe\">
      <legend>";
        // line 107
        echo twig_sprintf(_gettext("SQL query on database <b>%s</b>:"), ($context["db_link"] ?? null));
        echo "</legend>

      <textarea cols=\"80\" name=\"sql_query\" id=\"textSqlquery\" rows=\"";
        // line 109
        echo (((1 === twig_compare(twig_length_filter($this->env, ($context["criteria_tables"] ?? null)), 30))) ? ("15") : ("7"));
        echo "\" dir=\"ltr\" aria-label=\"";
echo _gettext("SQL query");
        echo "\">";
        // line 110
        echo twig_escape_filter($this->env, ($context["sql_query"] ?? null), "html", null, true);
        // line 111
        echo "</textarea>
    </fieldset>

    <fieldset class=\"pma-fieldset tblFooters\" id=\"tblQbeFooters\">
      <input class=\"btn btn-primary\" type=\"submit\" value=\"";
echo _gettext("Submit query");
        // line 115
        echo "\">
    </fieldset>
  </div>
</form>
";
    }

    public function getTemplateName()
    {
        return "database/qbe/selection_form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  259 => 115,  252 => 111,  250 => 110,  245 => 109,  240 => 107,  232 => 102,  228 => 101,  221 => 96,  214 => 92,  201 => 90,  197 => 89,  191 => 88,  187 => 86,  178 => 80,  162 => 67,  146 => 54,  133 => 45,  130 => 44,  123 => 41,  117 => 38,  114 => 37,  106 => 33,  103 => 32,  95 => 28,  92 => 27,  84 => 23,  81 => 22,  73 => 18,  70 => 17,  62 => 13,  59 => 12,  50 => 7,  42 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/qbe/selection_form.twig", "/var/www/html/public/phpmyadmin/templates/database/qbe/selection_form.twig");
    }
}
