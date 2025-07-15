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
class __TwigTemplate_6809622cc432c4b7120a261151b287f2 extends Template
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
        $this->parent = $this->load("base.html.twig", 3);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Inscripción de Voluntarios";
        yield from [];
    }

    // line 7
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 8
        yield "    <div class=\"container mx-auto p-4\">
        <h1 class=\"text-2xl font-bold mb-6\">Formulario de Inscripción de Voluntarios</h1>

        ";
        // line 12
        yield "        ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["app"] ?? null), "flashes", ["success"], "method", false, false, false, 12));
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
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["app"] ?? null), "flashes", ["error"], "method", false, false, false, 17));
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
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock(($context["form"] ?? null), 'form_start', ["attr" => ["class" => "bg-white p-6 rounded-lg shadow-md"]]);
        yield "

            ";
        // line 26
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Datos Personales ---</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>";
        // line 28
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "name", [], "any", false, false, false, 28), 'row');
        yield "</div>
                <div>";
        // line 29
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "lastName", [], "any", false, false, false, 29), 'row');
        yield "</div>
                <div>";
        // line 30
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "dni", [], "any", false, false, false, 30), 'row');
        yield "</div>
                <div>";
        // line 31
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "dateOfBirth", [], "any", false, false, false, 31), 'row');
        yield "</div>
                <div>";
        // line 32
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "streetType", [], "any", false, false, false, 32), 'row');
        yield "</div>
                <div>";
        // line 33
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "address", [], "any", false, false, false, 33), 'row');
        yield "</div>
                <div>";
        // line 34
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "postalCode", [], "any", false, false, false, 34), 'row');
        yield "</div>
                <div>";
        // line 35
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "province", [], "any", false, false, false, 35), 'row');
        yield "</div>
                <div>";
        // line 36
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "city", [], "any", false, false, false, 36), 'row');
        yield "</div>
                <div>";
        // line 37
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "phone", [], "any", false, false, false, 37), 'row');
        yield "</div>
            </div>

            ";
        // line 41
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Datos de Contacto de Emergencia ---</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>";
        // line 43
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "contactPerson1", [], "any", false, false, false, 43), 'row');
        yield "</div>
                <div>";
        // line 44
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "contactPhone1", [], "any", false, false, false, 44), 'row');
        yield "</div>
                <div>";
        // line 45
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "contactPerson2", [], "any", false, false, false, 45), 'row');
        yield "</div>
                <div>";
        // line 46
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "contactPhone2", [], "any", false, false, false, 46), 'row');
        yield "</div>
            </div>

            ";
        // line 50
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Datos de Salud ---</h2>
            <div class=\"mb-4\">
                ";
        // line 52
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "allergies", [], "any", false, false, false, 52), 'row');
        yield "
            </div>

            ";
        // line 56
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Datos Profesionales ---</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>";
        // line 58
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "profession", [], "any", false, false, false, 58), 'row');
        yield "</div>
                <div>";
        // line 59
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "employmentStatus", [], "any", false, false, false, 59), 'row');
        yield "</div>
            </div>
            <div class=\"mb-4\">
                <label class=\"block text-gray-700 text-sm font-bold mb-2\">Permiso de Conducción</label>
                ";
        // line 63
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "drivingLicenses", [], "any", false, false, false, 63), 'widget');
        yield " ";
        // line 64
        yield "                ";
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "drivingLicenses", [], "any", false, false, false, 64), 'errors');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 67
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "drivingLicenseExpiryDate", [], "any", false, false, false, 67), 'row');
        yield "
            </div>

            ";
        // line 71
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Otros Datos e Intereses ---</h2>
            <div class=\"mb-4\">
                ";
        // line 73
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "languages", [], "any", false, false, false, 73), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 76
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "motivation", [], "any", false, false, false, 76), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 79
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "howKnown", [], "any", false, false, false, 79), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                <label class=\"block text-gray-700 text-sm font-bold mb-2\">¿Ha realizado funciones de voluntariado con anterioridad?</label>
                ";
        // line 83
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "hasVolunteeredBefore", [], "any", false, false, false, 83), 'widget');
        yield "
                ";
        // line 84
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "hasVolunteeredBefore", [], "any", false, false, false, 84), 'errors');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 87
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "previousVolunteeringInstitutions", [], "any", false, false, false, 87), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                ";
        // line 90
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "otherQualifications", [], "any", false, false, false, 90), 'row');
        yield "
            </div>
            <div class=\"mb-4\">
                <label class=\"block text-gray-700 text-sm font-bold mb-2\">Permisos de Navegación</label>
                ";
        // line 94
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "navigationLicenses", [], "any", false, false, false, 94), 'widget');
        yield "
                ";
        // line 95
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "navigationLicenses", [], "any", false, false, false, 95), 'errors');
        yield "
            </div>

            ";
        // line 99
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Titulaciones Específicas ---</h2>
            <div class=\"mb-4\">
                ";
        // line 102
        yield "                ";
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "specificQualifications", [], "any", false, false, false, 102), 'row');
        yield "
            </div>

            ";
        // line 106
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Rol y Especialización ---</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>";
        // line 108
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "role", [], "any", false, false, false, 108), 'row');
        yield "</div>
                <div>";
        // line 109
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "specialization", [], "any", false, false, false, 109), 'row');
        yield "</div>
            </div>

            ";
        // line 113
        yield "            <h2 class=\"text-xl font-semibold mt-6 mb-4\">--- Datos de Acceso ---</h2>
            <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                <div>";
        // line 115
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "user", [], "any", false, false, false, 115), "email", [], "any", false, false, false, 115), 'row');
        yield "</div>
                <div>";
        // line 116
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "user", [], "any", false, false, false, 116), "password", [], "any", false, false, false, 116), 'row');
        yield "</div>
            </div>

            <button type=\"submit\" class=\"bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-6\">
                Enviar Solicitud
            </button>

        ";
        // line 123
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock(($context["form"] ?? null), 'form_end');
        yield "
    </div>
";
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
        return array (  325 => 123,  315 => 116,  311 => 115,  307 => 113,  301 => 109,  297 => 108,  293 => 106,  286 => 102,  282 => 99,  276 => 95,  272 => 94,  265 => 90,  259 => 87,  253 => 84,  249 => 83,  242 => 79,  236 => 76,  230 => 73,  226 => 71,  220 => 67,  213 => 64,  210 => 63,  203 => 59,  199 => 58,  195 => 56,  189 => 52,  185 => 50,  179 => 46,  175 => 45,  171 => 44,  167 => 43,  163 => 41,  157 => 37,  153 => 36,  149 => 35,  145 => 34,  141 => 33,  137 => 32,  133 => 31,  129 => 30,  125 => 29,  121 => 28,  117 => 26,  112 => 23,  109 => 22,  100 => 19,  97 => 18,  92 => 17,  83 => 14,  80 => 13,  75 => 12,  70 => 8,  63 => 7,  52 => 5,  41 => 3,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "volunteer/registration_form.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\volunteer\\registration_form.html.twig");
    }
}
