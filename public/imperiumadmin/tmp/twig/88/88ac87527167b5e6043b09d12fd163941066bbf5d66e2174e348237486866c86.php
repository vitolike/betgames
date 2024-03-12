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

/* server/replication/replica_configuration.twig */
class __TwigTemplate_ce8abf4c718cab5d1e39d87b5b308119f572f16153038f6f1cf1b52c1dda5cb3 extends Template
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
        echo "<div class=\"card\">
  <div class=\"card-header\">";
echo _gettext("Replica replication");
        // line 2
        echo "</div>
  <div class=\"card-body\">
  ";
        // line 4
        if (($context["server_replica_multi_replication"] ?? null)) {
            // line 5
            echo "    ";
echo _gettext("Primary connection:");
            // line 6
            echo "    <form method=\"get\" action=\"";
            echo PhpMyAdmin\Url::getFromRoute("/server/replication");
            echo "\">
      ";
            // line 7
            echo PhpMyAdmin\Url::getHiddenInputs(($context["url_params"] ?? null));
            echo "
      <select name=\"primary_connection\">
        <option value=\"\">";
echo _gettext("Default");
            // line 9
            echo "</option>
        ";
            // line 10
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["server_replica_multi_replication"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["server"]) {
                // line 11
                echo "          <option value=\"";
                echo twig_escape_filter($this->env, (($__internal_compile_0 = $context["server"]) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["Connection_name"] ?? null) : null), "html", null, true);
                echo "\"";
                echo (((0 === twig_compare(($context["primary_connection"] ?? null), (($__internal_compile_1 = $context["server"]) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["Connection_name"] ?? null) : null)))) ? (" selected") : (""));
                echo ">
            ";
                // line 12
                echo twig_escape_filter($this->env, (($__internal_compile_2 = $context["server"]) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["Connection_name"] ?? null) : null), "html", null, true);
                echo "
          </option>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['server'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 15
            echo "      </select>
      <input id=\"goButton\" class=\"btn btn-primary\" type=\"submit\" value=\"";
echo _gettext("Go");
            // line 16
            echo "\">
    </form>
    <br>
    <br>
  ";
        }
        // line 21
        echo "
  ";
        // line 22
        if (($context["server_replica_status"] ?? null)) {
            // line 23
            echo "    <div id=\"replica_configuration_gui\">
      ";
            // line 24
            if ( !($context["replica_sql_running"] ?? null)) {
                // line 25
                echo "        ";
                echo call_user_func_array($this->env->getFilter('error')->getCallable(), [_gettext("Replica SQL Thread not running!")]);
                echo "
      ";
            }
            // line 27
            echo "      ";
            if ( !($context["replica_io_running"] ?? null)) {
                // line 28
                echo "        ";
                echo call_user_func_array($this->env->getFilter('error')->getCallable(), [_gettext("Replica IO Thread not running!")]);
                echo "
      ";
            }
            // line 30
            echo "
      <p>";
echo _gettext("Server is configured as replica in a replication process. Would you like to:");
            // line 31
            echo "</p>
      <ul>
        <li>
          <a href=\"#replica_status_href\" id=\"replica_status_href\">";
echo _gettext("See replica status table");
            // line 34
            echo "</a>
          ";
            // line 35
            echo ($context["replica_status_table"] ?? null);
            echo "
        </li>
        <li>
          <a href=\"#replica_control_href\" id=\"replica_control_href\">";
echo _gettext("Control replica:");
            // line 38
            echo "</a>
          <div id=\"replica_control_gui\" class=\"hide\">
            <ul>
              <li>
                <a href=\"";
            // line 42
            echo PhpMyAdmin\Url::getFromRoute("/server/replication");
            echo "\" data-post=\"";
            echo ($context["replica_control_full_link"] ?? null);
            echo "\">
                  ";
            // line 43
            echo ((( !($context["replica_io_running"] ?? null) ||  !($context["replica_sql_running"] ?? null))) ? ("Full start") : ("Full stop"));
            echo "
                </a>
              </li>
              <li>
                <a class=\"ajax\" id=\"reset_replica\" href=\"";
            // line 47
            echo PhpMyAdmin\Url::getFromRoute("/server/replication");
            echo "\" data-post=\"";
            echo ($context["replica_control_reset_link"] ?? null);
            echo "\">
                  ";
echo _gettext("Reset replica");
            // line 49
            echo "                </a>
              </li>
              <li>
                <a href=\"";
            // line 52
            echo PhpMyAdmin\Url::getFromRoute("/server/replication");
            echo "\" data-post=\"";
            echo ($context["replica_control_sql_link"] ?? null);
            echo "\">
                  ";
            // line 53
            if ( !($context["replica_sql_running"] ?? null)) {
                // line 54
                echo "                    ";
echo _gettext("Start SQL Thread only");
                // line 55
                echo "                  ";
            } else {
                // line 56
                echo "                    ";
echo _gettext("Stop SQL Thread only");
                // line 57
                echo "                  ";
            }
            // line 58
            echo "                </a>
              </li>
              <li>
                <a href=\"";
            // line 61
            echo PhpMyAdmin\Url::getFromRoute("/server/replication");
            echo "\" data-post=\"";
            echo ($context["replica_control_io_link"] ?? null);
            echo "\">
                  ";
            // line 62
            if ( !($context["replica_io_running"] ?? null)) {
                // line 63
                echo "                    ";
echo _gettext("Start IO Thread only");
                // line 64
                echo "                  ";
            } else {
                // line 65
                echo "                    ";
echo _gettext("Stop IO Thread only");
                // line 66
                echo "                  ";
            }
            // line 67
            echo "                </a>
              </li>
            </ul>
          </div>
        </li>
        <li>
          <a href=\"#replica_errormanagement_href\" id=\"replica_errormanagement_href\">
            ";
echo _gettext("Error management:");
            // line 75
            echo "          </a>
          <div id=\"replica_errormanagement_gui\" class=\"hide\">
            ";
            // line 77
            echo call_user_func_array($this->env->getFilter('error')->getCallable(), [_gettext("Skipping errors might lead into unsynchronized primary and replica!")]);
            echo "
            <ul>
              <li>
                <a href=\"";
            // line 80
            echo PhpMyAdmin\Url::getFromRoute("/server/replication");
            echo "\" data-post=\"";
            echo ($context["replica_skip_error_link"] ?? null);
            echo "\">
                  ";
echo _gettext("Skip current error");
            // line 82
            echo "                </a>
              </li>
              <li>
                <form method=\"post\" action=\"";
            // line 85
            echo PhpMyAdmin\Url::getFromRoute("/server/replication");
            echo "\">
                  ";
            // line 86
            echo PhpMyAdmin\Url::getHiddenInputs("", "");
            echo "
                  ";
            // line 87
            echo twig_sprintf(_gettext("Skip next %s errors."), "<input type=\"text\" name=\"sr_skip_errors_count\" value=\"1\" class=\"repl_gui_skip_err_cnt\">");
            echo "
                  <input class=\"btn btn-primary\" type=\"submit\" name=\"sr_replica_skip_error\" value=\"";
echo _gettext("Go");
            // line 88
            echo "\">
                  <input type=\"hidden\" name=\"sr_take_action\" value=\"1\">
                </form>
              </li>
            </ul>
          </div>
        </li>
        <li>
          <a href=\"";
            // line 96
            echo PhpMyAdmin\Url::getFromRoute("/server/replication");
            echo "\" data-post=\"";
            echo ($context["reconfigure_primary_link"] ?? null);
            echo "\">
            ";
echo _gettext("Change or reconfigure primary server");
            // line 98
            echo "          </a>
        </li>
      </ul>
    </div>
  ";
        } elseif ( !        // line 102
($context["has_replica_configure"] ?? null)) {
            // line 103
            echo "    ";
            ob_start(function () { return ''; });
            // line 107
            echo "      ";
echo _gettext("This server is not configured as replica in a replication process. Would you like to %sconfigure%s it?");
            // line 108
            echo "    ";
            $___internal_parse_0_ = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 103
            echo twig_sprintf($___internal_parse_0_, (((("<a href=\"" . PhpMyAdmin\Url::getFromRoute("/server/replication")) . "\" data-post=\"") . PhpMyAdmin\Url::getCommon(twig_array_merge(($context["url_params"] ?? null), ["replica_configure" => true, "repl_clear_scr" => true]))) . "\">"), "</a>");
            // line 109
            echo "  ";
        }
        // line 110
        echo "  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "server/replication/replica_configuration.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  300 => 110,  297 => 109,  295 => 103,  292 => 108,  289 => 107,  286 => 103,  284 => 102,  278 => 98,  271 => 96,  261 => 88,  256 => 87,  252 => 86,  248 => 85,  243 => 82,  236 => 80,  230 => 77,  226 => 75,  216 => 67,  213 => 66,  210 => 65,  207 => 64,  204 => 63,  202 => 62,  196 => 61,  191 => 58,  188 => 57,  185 => 56,  182 => 55,  179 => 54,  177 => 53,  171 => 52,  166 => 49,  159 => 47,  152 => 43,  146 => 42,  140 => 38,  133 => 35,  130 => 34,  124 => 31,  120 => 30,  114 => 28,  111 => 27,  105 => 25,  103 => 24,  100 => 23,  98 => 22,  95 => 21,  88 => 16,  84 => 15,  75 => 12,  68 => 11,  64 => 10,  61 => 9,  55 => 7,  50 => 6,  47 => 5,  45 => 4,  41 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "server/replication/replica_configuration.twig", "/var/www/html/public/phpmyadmin/templates/server/replication/replica_configuration.twig");
    }
}
