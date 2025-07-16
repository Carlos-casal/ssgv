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

/* dashboard/index.html.twig */
class __TwigTemplate_731a8bb1753944bf18c48585bb9804af extends Template
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

        $this->blocks = [
            'page_title' => [$this, 'block_page_title'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "layout/app.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "dashboard/index.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "dashboard/index.html.twig"));

        $this->parent = $this->load("layout/app.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_page_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "page_title"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "page_title"));

        yield "Dashboard";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content"));

        // line 6
        yield "<div class=\"p-6 space-y-6\">
    <!-- Welcome section -->
    <div class=\"bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 text-white\">
        <h2 class=\"text-2xl font-bold mb-2\">¡Bienvenido al Sistema de Gestión!</h2>
        <p class=\"text-blue-100\">
            Gestiona voluntarios, recursos y servicios de manera eficiente desde un solo lugar.
        </p>
    </div>

    <!-- Stats cards -->
    <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6\">
        <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
            <div class=\"flex items-center justify-between\">
                <div>
                    <p class=\"text-sm font-medium text-gray-600\">Voluntarios Activos</p>
                    <p class=\"text-3xl font-bold text-gray-900 mt-2\">";
        // line 21
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 21, $this->source); })()), "total_volunteers", [], "any", false, false, false, 21), "html", null, true);
        yield "</p>
                    <div class=\"flex items-center mt-2\">
                        <span class=\"text-sm font-medium text-green-600\">+";
        // line 23
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 23, $this->source); })()), "total_volunteers", [], "any", false, false, false, 23) - CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 23, $this->source); })()), "active_volunteers", [], "any", false, false, false, 23)), "html", null, true);
        yield "</span>
                        <span class=\"text-sm text-gray-500 ml-1\">este mes</span>
                    </div>
                </div>
                <div class=\"p-3 bg-gray-50 rounded-lg\">
                    <i data-lucide=\"users\" class=\"w-6 h-6 text-blue-600\"></i>
                </div>
            </div>
        </div>

        <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
            <div class=\"flex items-center justify-between\">
                <div>
                    <p class=\"text-sm font-medium text-gray-600\">Servicios del Mes</p>
                    <p class=\"text-3xl font-bold text-gray-900 mt-2\">89</p>
                    <div class=\"flex items-center mt-2\">
                        <span class=\"text-sm font-medium text-green-600\">+5</span>
                        <span class=\"text-sm text-gray-500 ml-1\">este mes</span>
                    </div>
                </div>
                <div class=\"p-3 bg-gray-50 rounded-lg\">
                    <i data-lucide=\"calendar\" class=\"w-6 h-6 text-green-600\"></i>
                </div>
            </div>
        </div>

        <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
            <div class=\"flex items-center justify-between\">
                <div>
                    <p class=\"text-sm font-medium text-gray-600\">Vehículos Disponibles</p>
                    <p class=\"text-3xl font-bold text-gray-900 mt-2\">15</p>
                    <div class=\"flex items-center mt-2\">
                        <span class=\"text-sm font-medium text-red-600\">-2</span>
                        <span class=\"text-sm text-gray-500 ml-1\">este mes</span>
                    </div>
                </div>
                <div class=\"p-3 bg-gray-50 rounded-lg\">
                    <i data-lucide=\"car\" class=\"w-6 h-6 text-purple-600\"></i>
                </div>
            </div>
        </div>

        <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
            <div class=\"flex items-center justify-between\">
                <div>
                    <p class=\"text-sm font-medium text-gray-600\">Alertas Pendientes</p>
                    <p class=\"text-3xl font-bold text-gray-900 mt-2\">3</p>
                    <div class=\"flex items-center mt-2\">
                        <span class=\"text-sm font-medium text-gray-600\">0</span>
                        <span class=\"text-sm text-gray-500 ml-1\">este mes</span>
                    </div>
                </div>
                <div class=\"p-3 bg-gray-50 rounded-lg\">
                    <i data-lucide=\"alert-triangle\" class=\"w-6 h-6 text-orange-600\"></i>
                </div>
            </div>
        </div>
    </div>

    <div class=\"grid grid-cols-1 lg:grid-cols-2 gap-6\">
        <!-- Recent activities -->
        <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
            <h3 class=\"text-lg font-semibold text-gray-900 mb-4\">Actividad Reciente</h3>
            <div class=\"space-y-4\">
                ";
        // line 87
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["recent_activities"]) || array_key_exists("recent_activities", $context) ? $context["recent_activities"] : (function () { throw new RuntimeError('Variable "recent_activities" does not exist.', 87, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["activity"]) {
            // line 88
            yield "                    <div class=\"flex items-start gap-3\">
                        <div class=\"w-2 h-2 rounded-full mt-2 ";
            // line 89
            yield (((CoreExtension::getAttribute($this->env, $this->source, $context["activity"], "type", [], "any", false, false, false, 89) == "success")) ? ("bg-green-500") : ((((CoreExtension::getAttribute($this->env, $this->source, $context["activity"], "type", [], "any", false, false, false, 89) == "warning")) ? ("bg-orange-500") : ("bg-blue-500"))));
            yield "\"></div>
                        <div class=\"flex-1\">
                            <p class=\"text-sm font-medium text-gray-900\">";
            // line 91
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["activity"], "action", [], "any", false, false, false, 91), "html", null, true);
            yield "</p>
                            <p class=\"text-sm text-gray-600\">";
            // line 92
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["activity"], "user", [], "any", false, false, false, 92), "html", null, true);
            yield "</p>
                            <p class=\"text-xs text-gray-500 mt-1\">";
            // line 93
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["activity"], "time", [], "any", false, false, false, 93), "html", null, true);
            yield "</p>
                        </div>
                    </div>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['activity'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 97
        yield "            </div>
        </div>

        <!-- Upcoming events -->
        <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
            <h3 class=\"text-lg font-semibold text-gray-900 mb-4\">Próximos Eventos</h3>
            <div class=\"space-y-4\">
                ";
        // line 104
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["upcoming_events"]) || array_key_exists("upcoming_events", $context) ? $context["upcoming_events"] : (function () { throw new RuntimeError('Variable "upcoming_events" does not exist.', 104, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["event"]) {
            // line 105
            yield "                    <div class=\"border border-gray-200 rounded-lg p-4\">
                        <h4 class=\"font-medium text-gray-900\">";
            // line 106
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["event"], "title", [], "any", false, false, false, 106), "html", null, true);
            yield "</h4>
                        <div class=\"flex items-center gap-4 mt-2 text-sm text-gray-600\">
                            <span>📅 ";
            // line 108
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["event"], "date", [], "any", false, false, false, 108), "html", null, true);
            yield "</span>
                            <span>🕐 ";
            // line 109
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["event"], "time", [], "any", false, false, false, 109), "html", null, true);
            yield "</span>
                            <span>👥 ";
            // line 110
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["event"], "participants", [], "any", false, false, false, 110), "html", null, true);
            yield " participantes</span>
                        </div>
                    </div>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['event'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 114
        yield "            </div>
        </div>
    </div>

    <!-- Quick actions -->
    <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
        <h3 class=\"text-lg font-semibold text-gray-900 mb-4\">Acciones Rápidas</h3>
        <div class=\"grid grid-cols-1 md:grid-cols-3 gap-4\">
            <a href=\"";
        // line 122
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_new");
        yield "\" class=\"flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors\">
                <i data-lucide=\"users\" class=\"w-5 h-5 text-blue-600\"></i>
                <span class=\"font-medium\">Añadir Voluntario</span>
            </a>
            <button class=\"flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors\">
                <i data-lucide=\"calendar\" class=\"w-5 h-5 text-green-600\"></i>
                <span class=\"font-medium\">Programar Servicio</span>
            </button>
            <button class=\"flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors\">
                <i data-lucide=\"trending-up\" class=\"w-5 h-5 text-purple-600\"></i>
                <span class=\"font-medium\">Ver Estadísticas</span>
            </button>
        </div>
    </div>
</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "dashboard/index.html.twig";
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
        return array (  268 => 122,  258 => 114,  248 => 110,  244 => 109,  240 => 108,  235 => 106,  232 => 105,  228 => 104,  219 => 97,  209 => 93,  205 => 92,  201 => 91,  196 => 89,  193 => 88,  189 => 87,  122 => 23,  117 => 21,  100 => 6,  87 => 5,  64 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'layout/app.html.twig' %}

{% block page_title %}Dashboard{% endblock %}

{% block content %}
<div class=\"p-6 space-y-6\">
    <!-- Welcome section -->
    <div class=\"bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 text-white\">
        <h2 class=\"text-2xl font-bold mb-2\">¡Bienvenido al Sistema de Gestión!</h2>
        <p class=\"text-blue-100\">
            Gestiona voluntarios, recursos y servicios de manera eficiente desde un solo lugar.
        </p>
    </div>

    <!-- Stats cards -->
    <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6\">
        <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
            <div class=\"flex items-center justify-between\">
                <div>
                    <p class=\"text-sm font-medium text-gray-600\">Voluntarios Activos</p>
                    <p class=\"text-3xl font-bold text-gray-900 mt-2\">{{ stats.total_volunteers }}</p>
                    <div class=\"flex items-center mt-2\">
                        <span class=\"text-sm font-medium text-green-600\">+{{ stats.total_volunteers - stats.active_volunteers }}</span>
                        <span class=\"text-sm text-gray-500 ml-1\">este mes</span>
                    </div>
                </div>
                <div class=\"p-3 bg-gray-50 rounded-lg\">
                    <i data-lucide=\"users\" class=\"w-6 h-6 text-blue-600\"></i>
                </div>
            </div>
        </div>

        <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
            <div class=\"flex items-center justify-between\">
                <div>
                    <p class=\"text-sm font-medium text-gray-600\">Servicios del Mes</p>
                    <p class=\"text-3xl font-bold text-gray-900 mt-2\">89</p>
                    <div class=\"flex items-center mt-2\">
                        <span class=\"text-sm font-medium text-green-600\">+5</span>
                        <span class=\"text-sm text-gray-500 ml-1\">este mes</span>
                    </div>
                </div>
                <div class=\"p-3 bg-gray-50 rounded-lg\">
                    <i data-lucide=\"calendar\" class=\"w-6 h-6 text-green-600\"></i>
                </div>
            </div>
        </div>

        <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
            <div class=\"flex items-center justify-between\">
                <div>
                    <p class=\"text-sm font-medium text-gray-600\">Vehículos Disponibles</p>
                    <p class=\"text-3xl font-bold text-gray-900 mt-2\">15</p>
                    <div class=\"flex items-center mt-2\">
                        <span class=\"text-sm font-medium text-red-600\">-2</span>
                        <span class=\"text-sm text-gray-500 ml-1\">este mes</span>
                    </div>
                </div>
                <div class=\"p-3 bg-gray-50 rounded-lg\">
                    <i data-lucide=\"car\" class=\"w-6 h-6 text-purple-600\"></i>
                </div>
            </div>
        </div>

        <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
            <div class=\"flex items-center justify-between\">
                <div>
                    <p class=\"text-sm font-medium text-gray-600\">Alertas Pendientes</p>
                    <p class=\"text-3xl font-bold text-gray-900 mt-2\">3</p>
                    <div class=\"flex items-center mt-2\">
                        <span class=\"text-sm font-medium text-gray-600\">0</span>
                        <span class=\"text-sm text-gray-500 ml-1\">este mes</span>
                    </div>
                </div>
                <div class=\"p-3 bg-gray-50 rounded-lg\">
                    <i data-lucide=\"alert-triangle\" class=\"w-6 h-6 text-orange-600\"></i>
                </div>
            </div>
        </div>
    </div>

    <div class=\"grid grid-cols-1 lg:grid-cols-2 gap-6\">
        <!-- Recent activities -->
        <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
            <h3 class=\"text-lg font-semibold text-gray-900 mb-4\">Actividad Reciente</h3>
            <div class=\"space-y-4\">
                {% for activity in recent_activities %}
                    <div class=\"flex items-start gap-3\">
                        <div class=\"w-2 h-2 rounded-full mt-2 {{ activity.type == 'success' ? 'bg-green-500' : activity.type == 'warning' ? 'bg-orange-500' : 'bg-blue-500' }}\"></div>
                        <div class=\"flex-1\">
                            <p class=\"text-sm font-medium text-gray-900\">{{ activity.action }}</p>
                            <p class=\"text-sm text-gray-600\">{{ activity.user }}</p>
                            <p class=\"text-xs text-gray-500 mt-1\">{{ activity.time }}</p>
                        </div>
                    </div>
                {% endfor %}
            </div>
        </div>

        <!-- Upcoming events -->
        <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
            <h3 class=\"text-lg font-semibold text-gray-900 mb-4\">Próximos Eventos</h3>
            <div class=\"space-y-4\">
                {% for event in upcoming_events %}
                    <div class=\"border border-gray-200 rounded-lg p-4\">
                        <h4 class=\"font-medium text-gray-900\">{{ event.title }}</h4>
                        <div class=\"flex items-center gap-4 mt-2 text-sm text-gray-600\">
                            <span>📅 {{ event.date }}</span>
                            <span>🕐 {{ event.time }}</span>
                            <span>👥 {{ event.participants }} participantes</span>
                        </div>
                    </div>
                {% endfor %}
            </div>
        </div>
    </div>

    <!-- Quick actions -->
    <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
        <h3 class=\"text-lg font-semibold text-gray-900 mb-4\">Acciones Rápidas</h3>
        <div class=\"grid grid-cols-1 md:grid-cols-3 gap-4\">
            <a href=\"{{ path('app_volunteer_new') }}\" class=\"flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors\">
                <i data-lucide=\"users\" class=\"w-5 h-5 text-blue-600\"></i>
                <span class=\"font-medium\">Añadir Voluntario</span>
            </a>
            <button class=\"flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors\">
                <i data-lucide=\"calendar\" class=\"w-5 h-5 text-green-600\"></i>
                <span class=\"font-medium\">Programar Servicio</span>
            </button>
            <button class=\"flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors\">
                <i data-lucide=\"trending-up\" class=\"w-5 h-5 text-purple-600\"></i>
                <span class=\"font-medium\">Ver Estadísticas</span>
            </button>
        </div>
    </div>
</div>
{% endblock %}", "dashboard/index.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\dashboard\\index.html.twig");
    }
}
