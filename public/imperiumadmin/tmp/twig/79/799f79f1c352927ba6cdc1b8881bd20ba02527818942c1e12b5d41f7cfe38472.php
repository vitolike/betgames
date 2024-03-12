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

/* database/events/index.twig */
class __TwigTemplate_7f76c6824d3573dddb3401e50984c2a0f28e6e87200f3ba599907e3949dd6539 extends Template
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
        echo "<div class=\"container-fluid my-3\">
  <h2>
    ";
        // line 3
        echo PhpMyAdmin\Html\Generator::getIcon("b_events", _gettext("Events"));
        echo "
    ";
        // line 4
        echo PhpMyAdmin\Html\MySQLDocumentation::show("EVENTS");
        echo "
  </h2>

  <div class=\"d-flex flex-wrap my-3\">
    <div>
      <div class=\"input-group\">
        <div class=\"input-group-text\">
          <div class=\"form-check mb-0\">
            <input class=\"form-check-input checkall_box\" type=\"checkbox\" value=\"\" id=\"checkAllCheckbox\" form=\"rteListForm\">
            <label class=\"form-check-label\" for=\"checkAllCheckbox\">";
echo _gettext("Check all");
        // line 13
        echo "</label>
          </div>
        </div>
        <button class=\"btn btn-outline-secondary\" id=\"bulkActionExportButton\" type=\"submit\" name=\"submit_mult\" value=\"export\" form=\"rteListForm\" title=\"";
echo _gettext("Export");
        // line 16
        echo "\">
          ";
        // line 17
        echo PhpMyAdmin\Html\Generator::getIcon("b_export", _gettext("Export"));
        echo "
        </button>
        <button class=\"btn btn-outline-secondary\" id=\"bulkActionDropButton\" type=\"submit\" name=\"submit_mult\" value=\"drop\" form=\"rteListForm\" title=\"";
echo _gettext("Drop");
        // line 19
        echo "\">
          ";
        // line 20
        echo PhpMyAdmin\Html\Generator::getIcon("b_drop", _gettext("Drop"));
        echo "
        </button>
      </div>
    </div>

    <div class=\"ms-auto\">
      <a class=\"ajax add_anchor btn btn-primary";
        // line 26
        echo (( !($context["has_privilege"] ?? null)) ? (" disabled") : (""));
        echo "\" href=\"";
        echo PhpMyAdmin\Url::getFromRoute("/database/events", ["db" => ($context["db"] ?? null), "add_item" => true]);
        echo "\" role=\"button\"";
        echo (( !($context["has_privilege"] ?? null)) ? (" tabindex=\"-1\" aria-disabled=\"true\"") : (""));
        echo ">
        ";
        // line 27
        echo PhpMyAdmin\Html\Generator::getIcon("b_event_add", _gettext("Create new event"));
        echo "
      </a>
    </div>
  </div>

  <form id=\"rteListForm\" class=\"ajax\" action=\"";
        // line 32
        echo PhpMyAdmin\Url::getFromRoute("/database/events");
        echo "\">
    ";
        // line 33
        echo PhpMyAdmin\Url::getHiddenInputs(($context["db"] ?? null));
        echo "

    <div id=\"nothing2display\"";
        // line 35
        echo (( !twig_test_empty(($context["items"] ?? null))) ? (" class=\"hide\"") : (""));
        echo ">
      ";
echo _gettext("There are no events to display.");
        // line 37
        echo "    </div>

    <table id=\"eventsTable\" class=\"table table-light table-striped table-hover";
        // line 39
        echo ((twig_test_empty(($context["items"] ?? null))) ? (" hide") : (""));
        echo " w-auto data\">
      <thead class=\"table-light\">
      <tr>
        <th></th>
        <th>";
echo _gettext("Name");
        // line 43
        echo "</th>
        <th>";
echo _gettext("Status");
        // line 44
        echo "</th>
        <th>";
echo _gettext("Type");
        // line 45
        echo "</th>
        <th colspan=\"3\"></th>
      </tr>
      </thead>
      <tbody>
      <tr class=\"hide\">";
        // line 50
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(range(0, 6));
        foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
            echo "<td></td>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        echo "</tr>

      ";
        // line 52
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["event"]) {
            // line 53
            echo "        <tr";
            echo ((($context["is_ajax"] ?? null)) ? (" class=\"ajaxInsert hide\"") : (""));
            echo ">
          <td>
            <input type=\"checkbox\" class=\"checkall\" name=\"item_name[]\" value=\"";
            // line 55
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["event"], "name", [], "any", false, false, false, 55), "html", null, true);
            echo "\">
          </td>
          <td>
            <span class=\"drop_sql hide\">";
            // line 58
            echo twig_escape_filter($this->env, twig_sprintf("DROP EVENT IF EXISTS %s", PhpMyAdmin\Util::backquote(twig_get_attribute($this->env, $this->source, $context["event"], "name", [], "any", false, false, false, 58))), "html", null, true);
            echo "</span>
            <strong>";
            // line 59
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["event"], "name", [], "any", false, false, false, 59), "html", null, true);
            echo "</strong>
          </td>
          <td>
            ";
            // line 62
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["event"], "status", [], "any", false, false, false, 62), "html", null, true);
            echo "
          </td>
          <td>
            ";
            // line 65
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["event"], "type", [], "any", false, false, false, 65), "html", null, true);
            echo "
          </td>
          <td>
            ";
            // line 68
            if (($context["has_privilege"] ?? null)) {
                // line 69
                echo "              <a class=\"ajax edit_anchor\" href=\"";
                echo PhpMyAdmin\Url::getFromRoute("/database/events", ["db" =>                 // line 70
($context["db"] ?? null), "edit_item" => true, "item_name" => twig_get_attribute($this->env, $this->source,                 // line 72
$context["event"], "name", [], "any", false, false, false, 72)]);
                // line 73
                echo "\">
                ";
                // line 74
                echo PhpMyAdmin\Html\Generator::getIcon("b_edit", _gettext("Edit"));
                echo "
              </a>
            ";
            } else {
                // line 77
                echo "              ";
                echo PhpMyAdmin\Html\Generator::getIcon("bd_edit", _gettext("Edit"));
                echo "
            ";
            }
            // line 79
            echo "          </td>
          <td>
            <a class=\"ajax export_anchor\" href=\"";
            // line 81
            echo PhpMyAdmin\Url::getFromRoute("/database/events", ["db" =>             // line 82
($context["db"] ?? null), "export_item" => true, "item_name" => twig_get_attribute($this->env, $this->source,             // line 84
$context["event"], "name", [], "any", false, false, false, 84)]);
            // line 85
            echo "\">
              ";
            // line 86
            echo PhpMyAdmin\Html\Generator::getIcon("b_export", _gettext("Export"));
            echo "
            </a>
          </td>
          <td>
            ";
            // line 90
            if (($context["has_privilege"] ?? null)) {
                // line 91
                echo "              ";
                echo PhpMyAdmin\Html\Generator::linkOrButton(PhpMyAdmin\Url::getFromRoute("/sql"), ["db" =>                 // line 94
($context["db"] ?? null), "sql_query" => twig_sprintf("DROP EVENT IF EXISTS %s", PhpMyAdmin\Util::backquote(twig_get_attribute($this->env, $this->source,                 // line 95
$context["event"], "name", [], "any", false, false, false, 95))), "goto" => PhpMyAdmin\Url::getFromRoute("/database/events", ["db" =>                 // line 96
($context["db"] ?? null)])], PhpMyAdmin\Html\Generator::getIcon("b_drop", _gettext("Drop")), ["class" => "ajax drop_anchor"]);
                // line 100
                echo "
            ";
            } else {
                // line 102
                echo "              ";
                echo PhpMyAdmin\Html\Generator::getIcon("bd_drop", _gettext("Drop"));
                echo "
            ";
            }
            // line 104
            echo "          </td>
        </tr>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['event'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 107
        echo "      </tbody>
    </table>
  </form>

  <div class=\"card mt-3\">
    <div class=\"card-header\">";
echo _gettext("Event scheduler status");
        // line 112
        echo "</div>
    <div class=\"card-body\">
      <div class=\"wrap\">
        <div class=\"wrapper toggleAjax hide\">
          <div class=\"toggleButton\">
            <div title=\"";
echo _gettext("Click to toggle");
        // line 117
        echo "\" class=\"toggle-container ";
        echo ((($context["scheduler_state"] ?? null)) ? ("on") : ("off"));
        echo "\">
              <img src=\"";
        // line 118
        echo twig_escape_filter($this->env, $this->extensions['PhpMyAdmin\Twig\AssetExtension']->getImagePath((("toggle-" . ($context["text_dir"] ?? null)) . ".png")), "html", null, true);
        echo "\">
              <table>
                <tbody>
                <tr>
                  <td class=\"toggleOn\">
                  <span class=\"hide\">";
        // line 124
        echo PhpMyAdmin\Url::getFromRoute("/sql", ["db" =>         // line 125
($context["db"] ?? null), "goto" => PhpMyAdmin\Url::getFromRoute("/database/events", ["db" =>         // line 126
($context["db"] ?? null)]), "sql_query" => "SET GLOBAL event_scheduler=\"ON\""]);
        // line 129
        echo "</span>
                    <div>";
echo _gettext("ON");
        // line 130
        echo "</div>
                  </td>
                  <td><div>&nbsp;</div></td>
                  <td class=\"toggleOff\">
                  <span class=\"hide\">";
        // line 135
        echo PhpMyAdmin\Url::getFromRoute("/sql", ["db" =>         // line 136
($context["db"] ?? null), "goto" => PhpMyAdmin\Url::getFromRoute("/database/events", ["db" =>         // line 137
($context["db"] ?? null)]), "sql_query" => "SET GLOBAL event_scheduler=\"OFF\""]);
        // line 140
        echo "</span>
                    <div>";
echo _gettext("OFF");
        // line 141
        echo "</div>
                  </td>
                </tr>
                </tbody>
              </table>
              <span class=\"hide callback\">Functions.slidingMessage(data.sql_query);</span>
              <span class=\"hide text_direction\">";
        // line 147
        echo twig_escape_filter($this->env, ($context["text_dir"] ?? null), "html", null, true);
        echo "</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "database/events/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  317 => 147,  309 => 141,  305 => 140,  303 => 137,  302 => 136,  301 => 135,  295 => 130,  291 => 129,  289 => 126,  288 => 125,  287 => 124,  279 => 118,  274 => 117,  266 => 112,  258 => 107,  250 => 104,  244 => 102,  240 => 100,  238 => 96,  237 => 95,  236 => 94,  234 => 91,  232 => 90,  225 => 86,  222 => 85,  220 => 84,  219 => 82,  218 => 81,  214 => 79,  208 => 77,  202 => 74,  199 => 73,  197 => 72,  196 => 70,  194 => 69,  192 => 68,  186 => 65,  180 => 62,  174 => 59,  170 => 58,  164 => 55,  158 => 53,  154 => 52,  142 => 50,  135 => 45,  131 => 44,  127 => 43,  119 => 39,  115 => 37,  110 => 35,  105 => 33,  101 => 32,  93 => 27,  85 => 26,  76 => 20,  73 => 19,  67 => 17,  64 => 16,  58 => 13,  45 => 4,  41 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/events/index.twig", "/var/www/html/public/phpmyadmin/templates/database/events/index.twig");
    }
}
