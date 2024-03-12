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

/* database/multi_table_query/form.twig */
class __TwigTemplate_f7d414e8adf56eaf800598459f3a346e4dfacd5331b949c77c03a025a094f569 extends Template
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
        echo "<ul class=\"nav nav-pills m-2\">
  <li class=\"nav-item\">
    <a class=\"nav-link active\" href=\"";
        // line 3
        echo PhpMyAdmin\Url::getFromRoute("/database/multi-table-query", ["db" => ($context["db"] ?? null)]);
        echo "\">
      ";
echo _gettext("Multi-table query");
        // line 5
        echo "    </a>
  </li>

  <li class=\"nav-item\">
    <a class=\"nav-link\" href=\"";
        // line 9
        echo PhpMyAdmin\Url::getFromRoute("/database/qbe", ["db" => ($context["db"] ?? null)]);
        echo "\">
      ";
echo _gettext("Query by example");
        // line 11
        echo "    </a>
  </li>
</ul>

<div class=\"mb-3\">
  <button class=\"btn btn-sm btn-secondary\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#queryWindow\" aria-expanded=\"true\" aria-controls=\"queryWindow\">
    ";
echo _gettext("Query window");
        // line 18
        echo "  </button>
</div>
<div class=\"collapse show mb-3\" id=\"queryWindow\">

<form action=\"\" id=\"multi_table_query_form\" class=\"multi_table_query_form query_form\">
    <input type=\"hidden\" id=\"db_name\" value=\"";
        // line 23
        echo twig_escape_filter($this->env, ($context["db"] ?? null), "html", null, true);
        echo "\">
    <fieldset class=\"pma-fieldset\">
        ";
        // line 25
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["tables"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["table"]) {
            // line 26
            echo "            <div class=\"d-none\" id=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["table"], "hash", [], "any", false, false, false, 26), "html", null, true);
            echo "\">
                <option value=\"*\">*</option>
                ";
            // line 28
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["table"], "columns", [], "any", false, false, false, 28));
            foreach ($context['_seq'] as $context["_key"] => $context["column"]) {
                // line 29
                echo "                    <option value=\"";
                echo twig_escape_filter($this->env, $context["column"], "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $context["column"], "html", null, true);
                echo "</option>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['column'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 31
            echo "            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['table'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 33
        echo "
        ";
        // line 34
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(range(0, ($context["default_no_of_columns"] ?? null)));
        foreach ($context['_seq'] as $context["_key"] => $context["id"]) {
            // line 35
            echo "            ";
            if ((0 === twig_compare($context["id"], 0))) {
                echo "<div class=\"d-none\" id=\"new_column_layout\">";
            }
            // line 36
            echo "            <fieldset class=\"pma-fieldset column_details query-form__fieldset--inline position-relative\">
                <select class=\"tableNameSelect query-form__select--inline\">
                    <option value=\"\">";
echo _gettext("select table");
            // line 38
            echo "</option>
                    ";
            // line 39
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["tables"] ?? null));
            foreach ($context['_seq'] as $context["keyTableName"] => $context["table"]) {
                // line 40
                echo "                      <option data-hash=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["table"], "hash", [], "any", false, false, false, 40), "html", null, true);
                echo "\" value=\"";
                echo twig_escape_filter($this->env, $context["keyTableName"], "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $context["keyTableName"], "html", null, true);
                echo "</option>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['keyTableName'], $context['table'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 42
            echo "                </select>
                <span>.</span>
                <select class=\"columnNameSelect query-form__select--inline\">
                    <option value=\"\">";
echo _gettext("select column");
            // line 45
            echo "</option>
                </select>
                <br>
                <input type=\"checkbox\" checked=\"checked\" class=\"show_col\">
                <span>";
echo _gettext("Show");
            // line 49
            echo "</span>
                <br>
                <input type=\"text\" placeholder=\"";
echo _gettext("Table alias");
            // line 51
            echo "\" class=\"table_alias\">
                <input type=\"text\" placeholder=\"";
echo _gettext("Column alias");
            // line 52
            echo "\" class=\"col_alias\">
                <br>
                <input type=\"checkbox\"
                    title=\"";
echo _gettext("Use this column in criteria");
            // line 55
            echo "\"
                    class=\"criteria_col\">

                <button class=\"btn btn-link p-0 jsCriteriaButton\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#criteriaOptions";
            // line 58
            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
            echo "\" aria-expanded=\"false\" aria-controls=\"criteriaOptions";
            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
            echo "\">
                  ";
echo _gettext("criteria");
            // line 60
            echo "                </button>
                <div class=\"collapse jsCriteriaOptions\" id=\"criteriaOptions";
            // line 61
            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
            echo "\">

                <div>
                    <table class=\"table table-sm table-borderless align-middle w-auto\">

                        <tr class=\"sort_order query-form__tr--bg-none\">
                            <td>";
echo _gettext("Sort");
            // line 67
            echo "</td>
                            <td><input type=\"radio\" name=\"sort[";
            // line 68
            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
            echo "]\">";
echo _gettext("Ascending");
            echo "</td>
                            <td><input type=\"radio\" name=\"sort[";
            // line 69
            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
            echo "]\">";
echo _gettext("Descending");
            echo "</td>
                        </tr>

                        <tr class=\"logical_operator query-form__tr--bg-none query-form__tr--hide\">
                            <td>";
echo _gettext("Add as");
            // line 73
            echo "</td>
                            <td>
                                <input type=\"radio\"
                                    name=\"logical_op[";
            // line 76
            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
            echo "]\"
                                    value=\"AND\"
                                    class=\"logical_op\"
                                    checked=\"checked\">
                                AND
                            </td>
                            <td>
                                <input type=\"radio\"
                                    name=\"logical_op[";
            // line 84
            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
            echo "]\"
                                    value=\"OR\"
                                    class=\"logical_op\">
                                OR
                            </td>
                        </tr>

                        <tr class=\"query-form__tr--bg-none\">
                            <td>Op </td>
                            <td>
                                <select class=\"criteria_op\">
                                    <option value=\"=\">=</option>
                                    <option value=\">\">&gt;</option>
                                    <option value=\">=\">&gt;=</option>
                                    <option value=\"<\">&lt;</option>
                                    <option value=\"<=\">&lt;=</option>
                                    <option value=\"!=\">!=</option>
                                    <option value=\"LIKE\">LIKE</option>
                                    <option value=\"LIKE %...%\">LIKE %...%</option>
                                    <option value=\"NOT LIKE\">NOT LIKE</option>
                                    <option value=\"NOT LIKE %...%\">NOT LIKE %...%</option>
                                    <option value=\"IN (...)\">IN (...)</option>
                                    <option value=\"NOT IN (...)\">NOT IN (...)</option>
                                    <option value=\"BETWEEN\">BETWEEN</option>
                                    <option value=\"NOT BETWEEN\">NOT BETWEEN</option>
                                    <option value=\"IS NULL\">IS NULL</option>
                                    <option value=\"IS NOT NULL\">IS NOT NULL</option>
                                    <option value=\"REGEXP\">REGEXP</option>
                                    <option value=\"REGEXP ^...\$\">REGEXP ^...\$</option>
                                    <option value=\"NOT REGEXP\">NOT REGEXP</option>
                                </select>
                            </td>
                            <td>
                                <select class=\"criteria_rhs\">
                                    <option value=\"text\">";
echo _gettext("Text");
            // line 118
            echo "</option>
                                    <option value=\"anotherColumn\">";
echo _gettext("Another column");
            // line 119
            echo "</option>
                                </select>
                            </td>
                        </tr>

                        <tr class=\"rhs_table query-form__tr--hide query-form__tr--bg-none\">
                            <td></td>
                            <td>
                                <select  class=\"tableNameSelect\">
                                    <option value=\"\">";
echo _gettext("select table");
            // line 128
            echo "</option>
                                    ";
            // line 129
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["tables"] ?? null));
            foreach ($context['_seq'] as $context["keyTableName"] => $context["table"]) {
                // line 130
                echo "                                        <option data-hash=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["table"], "hash", [], "any", false, false, false, 130), "html", null, true);
                echo "\" value=\"";
                echo twig_escape_filter($this->env, $context["keyTableName"], "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $context["keyTableName"], "html", null, true);
                echo "</option>
                                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['keyTableName'], $context['table'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 132
            echo "                                </select><span>.</span>
                            </td>
                            <td>
                                <select class=\"columnNameSelect query-form__select--inline\">
                                    <option value=\"\">";
echo _gettext("select column");
            // line 136
            echo "</option>
                                </select>
                            </td>
                        </tr>

                        <tr class=\"rhs_text query-form__tr--bg-none\">
                            <td></td>
                            <td colspan=\"2\">
                                <input type=\"text\"
                                    class=\"rhs_text_val query-form__input--wide\"
                                    placeholder=\"";
echo _gettext("Enter criteria as free text");
            // line 146
            echo "\">
                            </td>
                        </tr>

                        </table>
                    </div>
                </div>
                <button type=\"button\" class=\"btn-close position-absolute top-0 end-0 jsRemoveColumn\" aria-label=\"";
echo _gettext("Remove this column");
            // line 153
            echo "\"></button>
            </fieldset>
            ";
            // line 155
            if ((0 === twig_compare($context["id"], 0))) {
                echo "</div>";
            }
            // line 156
            echo "        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['id'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 157
        echo "
        <fieldset class=\"pma-fieldset query-form__fieldset--inline\">
            <input class=\"btn btn-secondary\" type=\"button\" value=\"";
echo _gettext("+ Add column");
        // line 159
        echo "\" id=\"add_column_button\">
        </fieldset>

        <fieldset class=\"pma-fieldset\">
              ";
        // line 164
        echo "                <textarea id=\"MultiSqlquery\"
                    class=\"query-form__multi-sql-query\"
                    cols=\"80\"
                    rows=\"4\"
                    name=\"sql_query\"
                    dir=\"ltr\"></textarea>
        </fieldset>
    </fieldset>

    <fieldset class=\"pma-fieldset tblFooters\">
        <input class=\"btn btn-secondary\" type=\"button\" id=\"update_query_button\" value=\"";
echo _gettext("Update query");
        // line 174
        echo "\">
        <input class=\"btn btn-primary\" type=\"button\" id=\"submit_query\" value=\"";
echo _gettext("Submit query");
        // line 175
        echo "\">
    </fieldset>
</form>
</div>
<div id=\"sql_results\"></div>
";
    }

    public function getTemplateName()
    {
        return "database/multi_table_query/form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  381 => 175,  377 => 174,  364 => 164,  358 => 159,  353 => 157,  347 => 156,  343 => 155,  339 => 153,  329 => 146,  316 => 136,  309 => 132,  296 => 130,  292 => 129,  289 => 128,  277 => 119,  273 => 118,  235 => 84,  224 => 76,  219 => 73,  209 => 69,  203 => 68,  200 => 67,  190 => 61,  187 => 60,  180 => 58,  175 => 55,  169 => 52,  165 => 51,  160 => 49,  153 => 45,  147 => 42,  134 => 40,  130 => 39,  127 => 38,  122 => 36,  117 => 35,  113 => 34,  110 => 33,  103 => 31,  92 => 29,  88 => 28,  82 => 26,  78 => 25,  73 => 23,  66 => 18,  57 => 11,  52 => 9,  46 => 5,  41 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/multi_table_query/form.twig", "/var/www/html/public/phpmyadmin/templates/database/multi_table_query/form.twig");
    }
}
