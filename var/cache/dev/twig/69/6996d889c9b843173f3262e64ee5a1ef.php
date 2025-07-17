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

/* service/attendance.html.twig */
class __TwigTemplate_52926972bcd0b933f61306c8e213ce09 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "service/attendance.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "service/attendance.html.twig"));

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

        yield "Asistencia al Servicio: ";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["service"]) || array_key_exists("service", $context) ? $context["service"] : (function () { throw new RuntimeError('Variable "service" does not exist.', 3, $this->source); })()), "title", [], "any", false, false, false, 3), "html", null, true);
        
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
    <div class=\"flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4\">
        <div>
            <h2 class=\"text-2xl font-bold text-gray-900\">Asistencia al Servicio: ";
        // line 9
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["service"]) || array_key_exists("service", $context) ? $context["service"] : (function () { throw new RuntimeError('Variable "service" does not exist.', 9, $this->source); })()), "title", [], "any", false, false, false, 9), "html", null, true);
        yield "</h2>
            <p class=\"text-gray-600\">A continuación se muestra la lista de asistentes y no asistentes.</p>
        </div>
    </div>

    <div class=\"grid grid-cols-1 md:grid-cols-2 gap-6\">
        <div class=\"bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden\">
            <h3 class=\"text-lg font-bold text-gray-900 p-4 border-b\">Asistentes</h3>
            <div class=\"overflow-x-auto\">
                <table class=\"w-full\">
                    <thead class=\"bg-gray-50\">
                        <tr>
                            <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Nombre</th>
                            <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Apellidos</th>
                        </tr>
                    </thead>
                    <tbody class=\"divide-y divide-gray-200\">
                        ";
        // line 26
        $context["attendees"] = Twig\Extension\CoreExtension::filter($this->env, CoreExtension::getAttribute($this->env, $this->source, (isset($context["service"]) || array_key_exists("service", $context) ? $context["service"] : (function () { throw new RuntimeError('Variable "service" does not exist.', 26, $this->source); })()), "assistanceConfirmations", [], "any", false, false, false, 26), function ($__c__) use ($context, $macros) { $context["c"] = $__c__; return CoreExtension::getAttribute($this->env, $this->source, (isset($context["c"]) || array_key_exists("c", $context) ? $context["c"] : (function () { throw new RuntimeError('Variable "c" does not exist.', 26, $this->source); })()), "hasAttended", [], "any", false, false, false, 26); });
        // line 27
        yield "                        ";
        if ((($tmp =  !Twig\Extension\CoreExtension::testEmpty((isset($context["attendees"]) || array_key_exists("attendees", $context) ? $context["attendees"] : (function () { throw new RuntimeError('Variable "attendees" does not exist.', 27, $this->source); })()))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 28
            yield "                            ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((isset($context["attendees"]) || array_key_exists("attendees", $context) ? $context["attendees"] : (function () { throw new RuntimeError('Variable "attendees" does not exist.', 28, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["confirmation"]) {
                // line 29
                yield "                                <tr class=\"hover:bg-gray-50\">
                                    <td class=\"px-6 py-4\">";
                // line 30
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["confirmation"], "volunteer", [], "any", false, false, false, 30), "name", [], "any", false, false, false, 30), "html", null, true);
                yield "</td>
                                    <td class=\"px-6 py-4\">";
                // line 31
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["confirmation"], "volunteer", [], "any", false, false, false, 31), "lastName", [], "any", false, false, false, 31), "html", null, true);
                yield "</td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['confirmation'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 34
            yield "                        ";
        } else {
            // line 35
            yield "                            <tr>
                                <td colspan=\"2\" class=\"px-6 py-8 text-center text-gray-500\">
                                    No hay asistentes confirmados.
                                </td>
                            </tr>
                        ";
        }
        // line 41
        yield "                    </tbody>
                </table>
            </div>
        </div>

        <div class=\"bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden\">
            <h3 class=\"text-lg font-bold text-gray-900 p-4 border-b\">No Asistentes</h3>
            <div class=\"overflow-x-auto\">
                <table class=\"w-full\">
                    <thead class=\"bg-gray-50\">
                        <tr>
                            <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Nombre</th>
                            <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Apellidos</th>
                        </tr>
                    </thead>
                    <tbody class=\"divide-y divide-gray-200\">
                        ";
        // line 57
        $context["nonAttendees"] = Twig\Extension\CoreExtension::filter($this->env, CoreExtension::getAttribute($this->env, $this->source, (isset($context["service"]) || array_key_exists("service", $context) ? $context["service"] : (function () { throw new RuntimeError('Variable "service" does not exist.', 57, $this->source); })()), "assistanceConfirmations", [], "any", false, false, false, 57), function ($__c__) use ($context, $macros) { $context["c"] = $__c__; return  !CoreExtension::getAttribute($this->env, $this->source, (isset($context["c"]) || array_key_exists("c", $context) ? $context["c"] : (function () { throw new RuntimeError('Variable "c" does not exist.', 57, $this->source); })()), "hasAttended", [], "any", false, false, false, 57); });
        // line 58
        yield "                        ";
        if ((($tmp =  !Twig\Extension\CoreExtension::testEmpty((isset($context["nonAttendees"]) || array_key_exists("nonAttendees", $context) ? $context["nonAttendees"] : (function () { throw new RuntimeError('Variable "nonAttendees" does not exist.', 58, $this->source); })()))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 59
            yield "                            ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((isset($context["nonAttendees"]) || array_key_exists("nonAttendees", $context) ? $context["nonAttendees"] : (function () { throw new RuntimeError('Variable "nonAttendees" does not exist.', 59, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["confirmation"]) {
                // line 60
                yield "                                <tr class=\"hover:bg-gray-50\">
                                    <td class=\"px-6 py-4\">";
                // line 61
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["confirmation"], "volunteer", [], "any", false, false, false, 61), "name", [], "any", false, false, false, 61), "html", null, true);
                yield "</td>
                                    <td class=\"px-6 py-4\">";
                // line 62
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["confirmation"], "volunteer", [], "any", false, false, false, 62), "lastName", [], "any", false, false, false, 62), "html", null, true);
                yield "</td>
                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['confirmation'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 65
            yield "                        ";
        } else {
            // line 66
            yield "                            <tr>
                                <td colspan=\"2\" class=\"px-6 py-8 text-center text-gray-500\">
                                    No hay voluntarios que no asistan.
                                </td>
                            </tr>
                        ";
        }
        // line 72
        yield "                    </tbody>
                </table>
            </div>
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
        return "service/attendance.html.twig";
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
        return array (  218 => 72,  210 => 66,  207 => 65,  198 => 62,  194 => 61,  191 => 60,  186 => 59,  183 => 58,  181 => 57,  163 => 41,  155 => 35,  152 => 34,  143 => 31,  139 => 30,  136 => 29,  131 => 28,  128 => 27,  126 => 26,  106 => 9,  101 => 6,  88 => 5,  64 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'layout/app.html.twig' %}

{% block page_title %}Asistencia al Servicio: {{ service.title }}{% endblock %}

{% block content %}
<div class=\"p-6 space-y-6\">
    <div class=\"flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4\">
        <div>
            <h2 class=\"text-2xl font-bold text-gray-900\">Asistencia al Servicio: {{ service.title }}</h2>
            <p class=\"text-gray-600\">A continuación se muestra la lista de asistentes y no asistentes.</p>
        </div>
    </div>

    <div class=\"grid grid-cols-1 md:grid-cols-2 gap-6\">
        <div class=\"bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden\">
            <h3 class=\"text-lg font-bold text-gray-900 p-4 border-b\">Asistentes</h3>
            <div class=\"overflow-x-auto\">
                <table class=\"w-full\">
                    <thead class=\"bg-gray-50\">
                        <tr>
                            <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Nombre</th>
                            <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Apellidos</th>
                        </tr>
                    </thead>
                    <tbody class=\"divide-y divide-gray-200\">
                        {% set attendees = service.assistanceConfirmations|filter(c => c.hasAttended) %}
                        {% if attendees is not empty %}
                            {% for confirmation in attendees %}
                                <tr class=\"hover:bg-gray-50\">
                                    <td class=\"px-6 py-4\">{{ confirmation.volunteer.name }}</td>
                                    <td class=\"px-6 py-4\">{{ confirmation.volunteer.lastName }}</td>
                                </tr>
                            {% endfor %}
                        {% else %}
                            <tr>
                                <td colspan=\"2\" class=\"px-6 py-8 text-center text-gray-500\">
                                    No hay asistentes confirmados.
                                </td>
                            </tr>
                        {% endif %}
                    </tbody>
                </table>
            </div>
        </div>

        <div class=\"bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden\">
            <h3 class=\"text-lg font-bold text-gray-900 p-4 border-b\">No Asistentes</h3>
            <div class=\"overflow-x-auto\">
                <table class=\"w-full\">
                    <thead class=\"bg-gray-50\">
                        <tr>
                            <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Nombre</th>
                            <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Apellidos</th>
                        </tr>
                    </thead>
                    <tbody class=\"divide-y divide-gray-200\">
                        {% set nonAttendees = service.assistanceConfirmations|filter(c => not c.hasAttended) %}
                        {% if nonAttendees is not empty %}
                            {% for confirmation in nonAttendees %}
                                <tr class=\"hover:bg-gray-50\">
                                    <td class=\"px-6 py-4\">{{ confirmation.volunteer.name }}</td>
                                    <td class=\"px-6 py-4\">{{ confirmation.volunteer.lastName }}</td>
                                </tr>
                            {% endfor %}
                        {% else %}
                            <tr>
                                <td colspan=\"2\" class=\"px-6 py-8 text-center text-gray-500\">
                                    No hay voluntarios que no asistan.
                                </td>
                            </tr>
                        {% endif %}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{% endblock %}
", "service/attendance.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\service\\attendance.html.twig");
    }
}
