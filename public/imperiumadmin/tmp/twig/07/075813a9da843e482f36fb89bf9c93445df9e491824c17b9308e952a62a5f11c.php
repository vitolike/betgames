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

/* server/replication/index.twig */
class __TwigTemplate_385eb678f7907fc1eb8be358a9526fe1b7d9fbb99764054af3a51d5bca78038c extends Template
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
        echo "<div class=\"container-fluid\">
<div class=\"row\">
<h2>
  ";
        // line 4
        echo PhpMyAdmin\Html\Generator::getImage("s_replication");
        echo "
  ";
echo _gettext("Replication");
        // line 6
        echo "</h2>
</div>

";
        // line 9
        if (($context["is_super_user"] ?? null)) {
            // line 10
            echo "<div class=\"row\">
  <div id=\"replication\" class=\"container-fluid\">
    ";
            // line 12
            echo ($context["error_messages"] ?? null);
            echo "

    ";
            // line 14
            if (($context["is_primary"] ?? null)) {
                // line 15
                echo "      ";
                echo ($context["primary_replication_html"] ?? null);
                echo "
    ";
            } elseif (((null ===             // line 16
($context["primary_configure"] ?? null)) && (null === ($context["clear_screen"] ?? null)))) {
                // line 17
                echo "      <div class=\"card mb-2\">
        <div class=\"card-header\">";
echo _gettext("Primary replication");
                // line 18
                echo "</div>
        <div class=\"card-body\">
        ";
                // line 20
                ob_start(function () { return ''; });
                // line 21
                echo "          ";
echo _gettext("This server is not configured as primary in a replication process. Would you like to %sconfigure%s it?");
                // line 24
                echo "        ";
                $___internal_parse_1_ = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
                // line 20
                echo twig_sprintf($___internal_parse_1_, (((("<a href=\"" . PhpMyAdmin\Url::getFromRoute("/server/replication")) . "\" data-post=\"") . PhpMyAdmin\Url::getCommon(twig_array_merge(($context["url_params"] ?? null), ["primary_configure" => true]), "", false)) . "\">"), "</a>");
                // line 25
                echo "        </div>
      </div>
    ";
            }
            // line 28
            echo "
    ";
            // line 29
            if ( !(null === ($context["primary_configure"] ?? null))) {
                // line 30
                echo "      ";
                echo ($context["primary_configuration_html"] ?? null);
                echo "
    ";
            } else {
                // line 32
                echo "      ";
                if ((null === ($context["clear_screen"] ?? null))) {
                    // line 33
                    echo "        ";
                    echo ($context["replica_configuration_html"] ?? null);
                    echo "
      ";
                }
                // line 35
                echo "      ";
                if ( !(null === ($context["replica_configure"] ?? null))) {
                    // line 36
                    echo "        ";
                    echo ($context["change_primary_html"] ?? null);
                    echo "
      ";
                }
                // line 38
                echo "    ";
            }
            // line 39
            echo "  </div>
</div>
</div>
";
        } else {
            // line 43
            echo "  ";
            echo call_user_func_array($this->env->getFilter('error')->getCallable(), [_gettext("No privileges")]);
            echo "
";
        }
    }

    public function getTemplateName()
    {
        return "server/replication/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  133 => 43,  127 => 39,  124 => 38,  118 => 36,  115 => 35,  109 => 33,  106 => 32,  100 => 30,  98 => 29,  95 => 28,  90 => 25,  88 => 20,  85 => 24,  82 => 21,  80 => 20,  76 => 18,  72 => 17,  70 => 16,  65 => 15,  63 => 14,  58 => 12,  54 => 10,  52 => 9,  47 => 6,  42 => 4,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "server/replication/index.twig", "/var/www/html/public/phpmyadmin/templates/server/replication/index.twig");
    }
}
