<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* layout/sidebar.html.twig */
class __TwigTemplate_8c77781e3a3a107cf4002434ea6dd999 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<div class=\"bg-white shadow-lg w-80 flex flex-col h-full\" id=\"sidebar\">
    <!-- Header -->
    <div class=\"p-4 border-b border-gray-200\">
        <div class=\"flex items-center gap-3\">
            <div class=\"w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center\">
                <i data-lucide=\"users\" class=\"w-5 h-5 text-white\"></i>
            </div>
            <div>
                <h2 class=\"font-semibold text-gray-900\">SGV</h2>
                <p class=\"text-xs text-gray-500\">Sistema Gestión Voluntarios</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class=\"flex-1 p-4 overflow-y-auto\">
        <nav class=\"space-y-2\">
            <!-- Inicio -->
            <a href=\"";
        // line 19
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_dashboard");
        yield "\" 
               class=\"flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 ";
        // line 20
        yield (((((array_key_exists("current_section", $context)) ? (Twig\Extension\CoreExtension::default(($context["current_section"] ?? null), "")) : ("")) == "inicio")) ? ("bg-blue-100 text-blue-700 font-medium") : ("text-gray-700 hover:bg-gray-100"));
        yield "\">
                <i data-lucide=\"home\" class=\"w-5 h-5\"></i>
                <span class=\"text-sm\">Inicio</span>
            </a>

            <!-- Gestión -->
            <div class=\"mb-1\">
                <div class=\"flex items-center gap-3 px-3 py-2 text-gray-700 cursor-pointer\" onclick=\"toggleSubmenu('gestion')\">
                    <i data-lucide=\"users\" class=\"w-5 h-5\"></i>
                    <span class=\"flex-1 text-sm\">Gestión</span>
                    <i data-lucide=\"chevron-down\" class=\"w-4 h-4\" id=\"gestion-icon\"></i>
                </div>
                <div class=\"ml-4 space-y-1\" id=\"gestion-submenu\">
                    <!-- Personal -->
                    <div class=\"mb-1\">
                        <div class=\"flex items-center gap-3 px-3 py-2 text-gray-700 cursor-pointer\" onclick=\"toggleSubmenu('personal')\">
                            <i data-lucide=\"users\" class=\"w-4 h-4\"></i>
                            <span class=\"flex-1 text-sm\">Personal</span>
                            <i data-lucide=\"chevron-down\" class=\"w-4 h-4\" id=\"personal-icon\"></i>
                        </div>
                        <div class=\"ml-4 space-y-1\" id=\"personal-submenu\">
                            <a href=\"";
        // line 41
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_list");
        yield "\" 
                               class=\"flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 ";
        // line 42
        yield (((((array_key_exists("current_section", $context)) ? (Twig\Extension\CoreExtension::default(($context["current_section"] ?? null), "")) : ("")) == "personal-listado")) ? ("bg-blue-100 text-blue-700 font-medium") : ("text-gray-700 hover:bg-gray-100"));
        yield "\">
                                <i data-lucide=\"file-text\" class=\"w-4 h-4\"></i>
                                <span class=\"text-sm\">Listado</span>
                            </a>
                            <a href=\"";
        // line 46
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_reports");
        yield "\"
                               class=\"flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 ";
        // line 47
        yield (((((array_key_exists("current_section", $context)) ? (Twig\Extension\CoreExtension::default(($context["current_section"] ?? null), "")) : ("")) == "personal-informes")) ? ("bg-blue-100 text-blue-700 font-medium") : ("text-gray-700 hover:bg-gray-100"));
        yield "\">
                                <i data-lucide=\"bar-chart-3\" class=\"w-4 h-4\"></i>
                                <span class=\"text-sm\">Informes</span>
                            </a>
                        </div>
                    </div>

                    <!-- Servicios -->
                    <div class=\"mb-1\">
                        <div class=\"flex items-center gap-3 px-3 py-2 text-gray-700 cursor-pointer\" onclick=\"toggleSubmenu('servicios')\">
                            <i data-lucide=\"building\" class=\"w-4 h-4\"></i>
                            <span class=\"flex-1 text-sm\">Servicios</span>
                            <i data-lucide=\"chevron-down\" class=\"w-4 h-4\" id=\"servicios-icon\"></i>
                        </div>
                        <div class=\"ml-4 space-y-1\" id=\"servicios-submenu\" style=\"display: none;\">
                            <a href=\"";
        // line 62
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_services_list");
        yield "\" 
                               class=\"flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100\">
                                <i data-lucide=\"file-text\" class=\"w-4 h-4\"></i>
                                <span class=\"text-sm\">Listado</span>
                            </a>
                            <a href=\"";
        // line 67
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_services_reports");
        yield "\" 
                               class=\"flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100\">
                                <i data-lucide=\"bar-chart-3\" class=\"w-4 h-4\"></i>
                                <span class=\"text-sm\">Informes</span>
                            </a>
                            <a href=\"";
        // line 72
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_services_schedules");
        yield "\" 
                               class=\"flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100\">
                                <i data-lucide=\"check-square\" class=\"w-4 h-4\"></i>
                                <span class=\"text-sm\">Cuadrantes</span>
                            </a>
                        </div>
                    </div>

                    <!-- Comunicados -->
                    <a href=\"";
        // line 81
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_communications");
        yield "\" 
                       class=\"flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100\">
                        <i data-lucide=\"phone\" class=\"w-4 h-4\"></i>
                        <span class=\"text-sm\">Comunicados y Alertas</span>
                    </a>

                    <!-- Vehículos -->
                    <a href=\"";
        // line 88
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_vehicles");
        yield "\" 
                       class=\"flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100\">
                        <i data-lucide=\"car\" class=\"w-4 h-4\"></i>
                        <span class=\"text-sm\">Vehículos</span>
                    </a>
                </div>
            </div>

            <!-- GESDOC -->
            <a href=\"";
        // line 97
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_gesdoc");
        yield "\" 
               class=\"flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100\">
                <i data-lucide=\"file-text\" class=\"w-5 h-5\"></i>
                <span class=\"text-sm\">GESDOC</span>
            </a>

            <!-- CENTRAL -->
            <a href=\"";
        // line 104
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_central");
        yield "\" 
               class=\"flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100\">
                <i data-lucide=\"phone\" class=\"w-5 h-5\"></i>
                <span class=\"text-sm\">CENTRAL</span>
            </a>

            <!-- ESTADÍSTICAS -->
            <a href=\"";
        // line 111
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_statistics");
        yield "\" 
               class=\"flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 text-gray-700 hover:bg-gray-100\">
                <i data-lucide=\"bar-chart-3\" class=\"w-5 h-5\"></i>
                <span class=\"text-sm\">ESTADÍSTICAS</span>
            </a>
        </nav>
    </div>

    <!-- User section -->
    <div class=\"p-4 border-t border-gray-200\">
        <div class=\"flex items-center gap-3 mb-3\">
            <div class=\"w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center\">
                <i data-lucide=\"user\" class=\"w-4 h-4 text-gray-600\"></i>
            </div>
            <div class=\"flex-1\">
               ";
        // line 127
        yield "                <p class=\"text-xs text-gray-500\">";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["app"] ?? null), "user", [], "any", false, false, false, 127), "email", [], "any", false, false, false, 127), "html", null, true);
        yield "</p>
            </div>
        </div>
        <a href=\"";
        // line 130
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_logout");
        yield "\" 
           class=\"w-full flex items-center gap-3 px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors\">
            <i data-lucide=\"log-out\" class=\"w-5 h-5\"></i>
            <span class=\"text-sm\">Cerrar Sesión</span>
        </a>
    </div>
</div>

<script>
function toggleSubmenu(menuId) {
    const submenu = document.getElementById(menuId + '-submenu');
    const icon = document.getElementById(menuId + '-icon');
    
    if (submenu.style.display === 'none') {
        submenu.style.display = 'block';
        icon.setAttribute('data-lucide', 'chevron-down');
    } else {
        submenu.style.display = 'none';
        icon.setAttribute('data-lucide', 'chevron-right');
    }
    lucide.createIcons();
}

// Inicializar menús expandidos
document.addEventListener('DOMContentLoaded', function() {
    // Expandir menú de gestión por defecto
    const gestionSubmenu = document.getElementById('gestion-submenu');
    const personalSubmenu = document.getElementById('personal-submenu');
    
    if (gestionSubmenu) gestionSubmenu.style.display = 'block';
    if (personalSubmenu) personalSubmenu.style.display = 'block';
});
</script>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "layout/sidebar.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  218 => 130,  211 => 127,  193 => 111,  183 => 104,  173 => 97,  161 => 88,  151 => 81,  139 => 72,  131 => 67,  123 => 62,  105 => 47,  101 => 46,  94 => 42,  90 => 41,  66 => 20,  62 => 19,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "layout/sidebar.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\layout\\sidebar.html.twig");
    }
}
