import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets = ['purchasePrice', 'margin', 'iva', 'totalDisplay', 'ivaHint', 'alias', 'aliasWarning', 'submitBtn'];
    static values = {
        checkAliasUrl: String
    };

    connect() {
        this.aliasAvailable = true;
        this.calculate();
    }

    calculate() {
        if (!this.hasPurchasePriceTarget || !this.hasTotalDisplayTarget) {
            console.warn('KitRegistrationController: Missing vital targets for calculation.');
            return;
        }

        const purchasePrice = parseFloat(this.purchasePriceTarget.value) || 0;
        const margin = this.hasMarginTarget ? (parseFloat(this.marginTarget.value) || 0) : 0;
        const ivaVal = this.hasIvaTarget ? this.ivaTarget.value : 'included';

        console.log(`Calculating with Price: ${purchasePrice}, Margin: ${margin}, IVA: ${ivaVal}`);

        // Base price is the purchase price entered
        let basePrice = purchasePrice;
        
        // Final price before margin depends on IVA being included or not
        let priceWithIva = basePrice;
        if (ivaVal !== 'included') {
            const ivaRate = parseFloat(ivaVal) || 0;
            priceWithIva = basePrice * (1 + ivaRate);
        }

        // Margin is applied as a discount on the price-with-iva
        const discountAmount = priceWithIva * (margin / 100);
        const totalValuation = priceWithIva - discountAmount;

        console.log(`Intermediate Price: ${priceWithIva}, Discount: ${discountAmount}, Final: ${totalValuation}`);

        // IVA Hint visibility (trigger for 'included')
        if (this.hasIvaHintTarget) {
            if (ivaVal === 'included') {
                this.ivaHintTarget.classList.remove('d-none');
            } else {
                this.ivaHintTarget.classList.add('d-none');
            }
        }

        // Format and display
        this.totalDisplayTarget.innerText = totalValuation.toLocaleString('es-ES', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' €';
    }

    async checkAlias() {
        if (!this.hasAliasTarget || !this.hasAliasWarningTarget) return;

        const alias = this.aliasTarget.value.trim();
        if (alias.length < 3) {
            this.aliasWarningTarget.classList.add('d-none');
            this.aliasTarget.classList.remove('is-invalid');
            this.aliasAvailable = true;
            return;
        }

        try {
            const url = new URL(this.checkAliasUrlValue, window.location.origin);
            url.searchParams.append('alias', alias);
            
            const response = await fetch(url);
            const data = await response.json();
            
            if (!data.available) {
                this.aliasWarningTarget.classList.remove('d-none');
                this.aliasTarget.classList.add('is-invalid');
                this.aliasAvailable = false;
            } else {
                this.aliasWarningTarget.classList.add('d-none');
                this.aliasTarget.classList.remove('is-invalid');
                this.aliasAvailable = true;
            }
        } catch (e) {
            console.error('Error checking alias', e);
        }
    }

    handleSubmit(event) {
        if (!this.aliasAvailable) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Alias no disponible',
                text: 'Por favor, elige un identificador único para este botiquín.'
            });
        }
    }
}
