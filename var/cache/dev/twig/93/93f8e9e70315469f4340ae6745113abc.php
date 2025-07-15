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

/* volunteer/registration_form.html.twig */
class __TwigTemplate_f2abd8e2406cb6354dee75091ae22f79 extends Template
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
            'title' => [$this, 'block_title'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 3
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "volunteer/registration_form.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "volunteer/registration_form.html.twig"));

        $this->parent = $this->load("base.html.twig", 3);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        yield "Inscripción de Voluntarios";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 7
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 8
        yield "    <div class=\"container mx-auto p-4\">
        <h1 class=\"text-2xl font-bold mb-6\">Formulario de Inscripción de Voluntarios</h1>

        ";
        // line 12
        yield "        ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 12, $this->source); })()), "flashes", ["success"], "method", false, false, false, 12));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 13
            yield "            <div class=\"bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <span class=\"block sm:inline\">";
            // line 14
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
            yield "</span>
            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 17
        yield "        ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 17, $this->source); })()), "flashes", ["error"], "method", false, false, false, 17));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 18
            yield "            <div class=\"bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <span class=\"block sm:inline\">";
            // line 19
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
            yield "</span>
            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 22
        yield "
        ";
        // line 23
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 23, $this->source); })()), 'form_start', ["attr" => ["class" => "bg-white p-6 rounded-lg shadow-md"]]);
        yield "

            ";
        // line 26
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Datos Personales ---</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>";
        // line 28
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 28, $this->source); })()), "name", [], "any", false, false, false, 28), 'row');
        yield "</div>
                <div>";
        // line 29
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 29, $this->source); })()), "lastName", [], "any", false, false, false, 29), 'row');
        yield "</div>
                <div>";
        // line 30
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 30, $this->source); })()), "dni", [], "any", false, false, false, 30), 'row');
        yield "</div>
                <div>";
        // line 31
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 31, $this->source); })()), "dateOfBirth", [], "any", false, false, false, 31), 'row');
        yield "</div>
                <div>";
        // line 32
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 32, $this->source); })()), "streetType", [], "any", false, false, false, 32), 'row');
        yield "</div>
                <div>";
        // line 33
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 33, $this->source); })()), "address", [], "any", false, false, false, 33), 'row');
        yield "</div>
                <div>";
        // line 34
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 34, $this->source); })()), "postalCode", [], "any", false, false, false, 34), 'row');
        yield "</div>
                <div>";
        // line 35
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 35, $this->source); })()), "province", [], "any", false, false, false, 35), 'row');
        yield "</div>
                <div>";
        // line 36
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 36, $this->source); })()), "city", [], "any", false, false, false, 36), 'row');
        yield "</div>
                <div>";
        // line 37
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 37, $this->source); })()), "phone", [], "any", false, false, false, 37), 'row');
        yield "</div>
            </div>

            ";
        // line 41
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Datos de Contacto de Emergencia ---</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>";
        // line 43
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 43, $this->source); })()), "contactPerson1", [], "any", false, false, false, 43), 'row');
        yield "</div>
                <div>";
        // line 44
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 44, $this->source); })()), "contactPhone1", [], "any", false, false, false, 44), 'row');
        yield "</div>
                <div>";
        // line 45
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 45, $this->source); })()), "contactPerson2", [], "any", false, false, false, 45), 'row');
        yield "</div>
                <div>";
        // line 46
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 46, $this->source); })()), "contactPhone2", [], "any", false, false, false, 46), 'row');
        yield "</div>
            </div>

            ";
        // line 50
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Datos de Salud ---</h2>
            <div class=\"mb-4\">
                ";
        // line 52
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 52, $this->source); })()), "allergies", [], "any", false, false, false, 52), 'row');
        yield "
            </div>

            ";
        // line 56
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Datos Profesionales ---</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>";
        // line 58
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 58, $this->source); })()), "profession", [], "any", false, false, false, 58), 'row');
        yield "</div>
                <div>";
        // line 59
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 59, $this->source); })()), "employmentStatus", [], "any", false, false, false, 59), 'row');
        yield "</div>
            </div>
            <div class=\"mb-4\">
                <label class=\"block text-gray-700 text-sm font-bold mb-2\">Permiso de Conducción</label>
                ";
        // line 63
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 63, $this->source); })()), "drivingLicenses", [], "any", false, false, false, 63), 'widget');
        yield " ";
        // line 64
        yield "                ";
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 64, $this->source); })()), "drivingLicenses", [], "any", false, false, false, 64), 'errors');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 67
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 67, $this->source); })()), "drivingLicenseExpiryDate", [], "any", false, false, false, 67), 'row');
        yield "
            </div>

            ";
        // line 71
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Otros Datos e Intereses ---</h2>
            <div class=\"mb-4\">
                ";
        // line 73
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 73, $this->source); })()), "languages", [], "any", false, false, false, 73), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 76
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 76, $this->source); })()), "motivation", [], "any", false, false, false, 76), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 79
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 79, $this->source); })()), "howKnown", [], "any", false, false, false, 79), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                <label class=\"block text-gray-700 text-sm font-bold mb-2\">¿Ha realizado funciones de voluntariado con anterioridad?</label>
                ";
        // line 83
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 83, $this->source); })()), "hasVolunteeredBefore", [], "any", false, false, false, 83), 'widget');
        yield "
                ";
        // line 84
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 84, $this->source); })()), "hasVolunteeredBefore", [], "any", false, false, false, 84), 'errors');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 87
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 87, $this->source); })()), "previousVolunteeringInstitutions", [], "any", false, false, false, 87), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 90
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 90, $this->source); })()), "otherQualifications", [], "any", false, false, false, 90), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                <label class=\"block text-gray-700 text-sm font-bold mb-2\">Permisos de Navegación</label>
                ";
        // line 94
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 94, $this->source); })()), "navigationLicenses", [], "any", false, false, false, 94), 'widget');
        yield "
                ";
        // line 95
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 95, $this->source); })()), "navigationLicenses", [], "any", false, false, false, 95), 'errors');
        yield "
            </div>

            ";
        // line 99
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Titulaciones Específicas ---</h2>
            <div class=\"mb-4\">
                ";
        // line 102
        yield "                ";
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 102, $this->source); })()), "specificQualifications", [], "any", false, false, false, 102), 'row');
        yield "
            </div>

            ";
        // line 106
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Rol y Especialización ---</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>";
        // line 108
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 108, $this->source); })()), "role", [], "any", false, false, false, 108), 'row');
        yield "</div>
                <div>";
        // line 109
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 109, $this->source); })()), "specialization", [], "any", false, false, false, 109), 'row');
        yield "</div>
            </div>

            ";
        // line 113
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Datos de Acceso ---</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>";
        // line 115
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 115, $this->source); })()), "user", [], "any", false, false, false, 115), "email", [], "any", false, false, false, 115), 'row');
        yield "</div>
                <div>";
        // line 116
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 116, $this->source); })()), "user", [], "any", false, false, false, 116), "password", [], "any", false, false, false, 116), 'row');
        yield "</div>
            </div>

            <button type=\"submit\" class=\"bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-6\">
                Enviar Solicitud
            </button>

        ";
        // line 123
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 123, $this->source); })()), 'form_end');
        yield "
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
        return "volunteer/registration_form.html.twig";
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
        return array (  355 => 123,  345 => 116,  341 => 115,  337 => 113,  331 => 109,  327 => 108,  323 => 106,  316 => 102,  312 => 99,  306 => 95,  302 => 94,  295 => 90,  289 => 87,  283 => 84,  279 => 83,  272 => 79,  266 => 76,  260 => 73,  256 => 71,  250 => 67,  243 => 64,  240 => 63,  233 => 59,  229 => 58,  225 => 56,  219 => 52,  215 => 50,  209 => 46,  205 => 45,  201 => 44,  197 => 43,  193 => 41,  187 => 37,  183 => 36,  179 => 35,  175 => 34,  171 => 33,  167 => 32,  163 => 31,  159 => 30,  155 => 29,  151 => 28,  147 => 26,  142 => 23,  139 => 22,  130 => 19,  127 => 18,  122 => 17,  113 => 14,  110 => 13,  105 => 12,  100 => 8,  87 => 7,  64 => 5,  41 => 3,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{# templates/volunteer/registration_form.html.twig #}

{% extends 'base.html.twig' %}

{% block title %}Inscripción de Voluntarios{% endblock %}

{% block body %}
    <div class=\"container mx-auto p-4\">
        <h1 class=\"text-2xl font-bold mb-6\">Formulario de Inscripción de Voluntarios</h1>

        {# Mostrar mensajes flash #}
        {% for message in app.flashes('success') %}
            <div class=\"bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <span class=\"block sm:inline\">{{ message }}</span>
            </div>
        {% endfor %}
        {% for message in app.flashes('error') %}
            <div class=\"bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <span class=\"block sm:inline\">{{ message }}</span>
            </div>
        {% endfor %}

        {{ form_start(form, {'attr': {'class': 'bg-white p-6 rounded-lg shadow-md'}}) }}

            {# Sección: Datos Personales #}
            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Datos Personales ---</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>{{ form_row(form.name) }}</div>
                <div>{{ form_row(form.lastName) }}</div>
                <div>{{ form_row(form.dni) }}</div>
                <div>{{ form_row(form.dateOfBirth) }}</div>
                <div>{{ form_row(form.streetType) }}</div>
                <div>{{ form_row(form.address) }}</div>
                <div>{{ form_row(form.postalCode) }}</div>
                <div>{{ form_row(form.province) }}</div>
                <div>{{ form_row(form.city) }}</div>
                <div>{{ form_row(form.phone) }}</div>
            </div>

            {# Sección: Datos de Contacto de Emergencia #}
            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Datos de Contacto de Emergencia ---</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>{{ form_row(form.contactPerson1) }}</div>
                <div>{{ form_row(form.contactPhone1) }}</div>
                <div>{{ form_row(form.contactPerson2) }}</div>
                <div>{{ form_row(form.contactPhone2) }}</div>
            </div>

            {# Sección: Datos de Salud #}
            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Datos de Salud ---</h2>
            <div class=\"mb-4\">
                {{ form_row(form.allergies) }}
            </div>

            {# Sección: Datos Profesionales #}
            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Datos Profesionales ---</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>{{ form_row(form.profession) }}</div>
                <div>{{ form_row(form.employmentStatus) }}</div>
            </div>
            <div class=\"mb-4\">
                <label class=\"block text-gray-700 text-sm font-bold mb-2\">Permiso de Conducción</label>
                {{ form_widget(form.drivingLicenses) }} {# Muestra los checkboxes #}
                {{ form_errors(form.drivingLicenses) }}
            </div>
            <div class=\"mb-4\">
                {{ form_row(form.drivingLicenseExpiryDate) }}
            </div>

            {# Sección: Otros Datos e Intereses #}
            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Otros Datos e Intereses ---</h2>
            <div class=\"mb-4\">
                {{ form_row(form.languages) }}
            </div>
            <div class=\"mb-4\">
                {{ form_row(form.motivation) }}
            </div>
            <div class=\"mb-4\">
                {{ form_row(form.howKnown) }}
            </div>
            <div class=\"mb-4\">
                <label class=\"block text-gray-700 text-sm font-bold mb-2\">¿Ha realizado funciones de voluntariado con anterioridad?</label>
                {{ form_widget(form.hasVolunteeredBefore) }}
                {{ form_errors(form.hasVolunteeredBefore) }}
            </div>
            <div class=\"mb-4\">
                {{ form_row(form.previousVolunteeringInstitutions) }}
            </div>
            <div class=\"mb-4\">
                {{ form_row(form.otherQualifications) }}
            </div>
            <div class=\"mb-4\">
                <label class=\"block text-gray-700 text-sm font-bold mb-2\">Permisos de Navegación</label>
                {{ form_widget(form.navigationLicenses) }}
                {{ form_errors(form.navigationLicenses) }}
            </div>

            {# Sección: Titulaciones Específicas (AHORA AGRUPADAS) #}
            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Titulaciones Específicas ---</h2>
            <div class=\"mb-4\">
                {# Renderiza el nuevo campo de titulaciones específicas como checkboxes #}
                {{ form_row(form.specificQualifications) }}
            </div>

            {# Sección: Rol y Especialización #}
            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Rol y Especialización ---</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>{{ form_row(form.role) }}</div>
                <div>{{ form_row(form.specialization) }}</div>
            </div>

            {# Sección: Datos de Acceso #}
            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Datos de Acceso ---</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>{{ form_row(form.user.email) }}</div>
                <div>{{ form_row(form.user.password) }}</div>
            </div>

            <button type=\"submit\" class=\"bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-6\">
                Enviar Solicitud
            </button>

        {{ form_end(form) }}
    </div>
{% endblock %}", "volunteer/registration_form.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\volunteer\\registration_form.html.twig");
    }
}
