import React, { useRef, useState } from 'react';
import { NumericFormat } from 'react-number-format';
import { Trash2, Image as ImageIcon, Camera } from 'lucide-react';

export interface BatchRowData {
  id: string;
  batchNumber: string;
  expirationDate: string;
  supplier: string;
  unitsPerPackage: number | undefined;
  numPackages: number | undefined;
  tariff: number | undefined;
  discount: number | undefined;
  iva: number | undefined;
  image?: string;
}

interface BatchRegisterRowProps {
  index: number;
  data: BatchRowData;
  onChange: (updatedData: Partial<BatchRowData>) => void;
  onDelete: () => void;
}

export const BatchRegisterRow: React.FC<BatchRegisterRowProps> = ({
  index,
  data,
  onChange,
  onDelete,
}) => {
  const fileInputRef = useRef<HTMLInputElement>(null);
  const [imagePreview, setImagePreview] = useState<string | undefined>(data.image);

  // European currency/number formatting helper
  const formatEuropean = (value: number) => {
    return new Intl.NumberFormat('es-ES', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }).format(value);
  };

  // Default values
  const unitsPerPackage = data.unitsPerPackage ?? 1;
  const numPackages = data.numPackages ?? 1;
  const tariff = data.tariff ?? 0;
  const discount = data.discount ?? 0;
  const iva = data.iva ?? 0;

  // Formulas matching the Twig file:
  // pIva = (t * (1 - (d/100))) * (1 + (i/100))
  // total = pIva * n
  // pUd = u > 0 ? (pIva / u) : 0
  const discountMultiplier = 1 - discount / 100;
  const ivaMultiplier = 1 + iva / 100;
  const priceWithTaxAndDiscount = tariff * discountMultiplier * ivaMultiplier;

  const total = priceWithTaxAndDiscount * numPackages;
  const pUd = unitsPerPackage > 0 ? priceWithTaxAndDiscount / unitsPerPackage : 0;

  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.onload = (event) => {
        const previewUrl = event.target?.result as string;
        setImagePreview(previewUrl);
        onChange({ image: previewUrl });
      };
      reader.readAsDataURL(file);
    }
  };

  const triggerFileInput = () => {
    fileInputRef.current?.click();
  };

  const limitIntegerDigits = (val: string, maxDigits: number) => {
    if (!val) return true;
    const cleanVal = val.replace(/[^\d]/g, '');
    return cleanVal.length <= maxDigits;
  };

  return (
    <div className="relative bg-[#111827] border border-slate-800 rounded-xl p-5 sm:p-6 mb-6 shadow-2xl transition-all duration-300 hover:border-slate-700/60 group pt-10">
      
      {/* Legend / Title (Fitted to the exact theme from templates) */}
      <div className="absolute -top-3.5 left-6 bg-[#0b0f19] px-3.5 py-1 rounded-md border border-slate-800 text-[10px] font-black text-slate-400 tracking-wider flex items-center gap-2">
        <span>REGISTRO LOTE {index}</span>
        <button
          type="button"
          onClick={onDelete}
          className="text-slate-500 hover:text-red-500 transition-colors ml-2 focus:outline-none cursor-pointer"
          title="Eliminar lote"
        >
          <Trash2 className="w-3.5 h-3.5" />
        </button>
      </div>

      {/* Grid 1: Basic Lote Metadata */}
      <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        {/* Nº Lote */}
        <div className="flex flex-col">
          <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1">
            <span>Nº Lote</span>
            <span className="text-red-500">*</span>
          </label>
          <input
            type="text"
            value={data.batchNumber}
            onChange={(e) => onChange({ batchNumber: e.target.value })}
            className="w-full h-10 px-3 text-sm font-semibold text-slate-100 bg-[#0f172a] border border-slate-800 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder-slate-700"
            placeholder="Ej: LOTE-A205"
          />
        </div>

        {/* Caducidad */}
        <div className="flex flex-col">
          <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
            Caducidad
          </label>
          <input
            type="date"
            value={data.expirationDate}
            onChange={(e) => onChange({ expirationDate: e.target.value })}
            className="w-full h-10 px-3 text-sm font-semibold text-slate-100 bg-[#0f172a] border border-slate-800 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all [color-scheme:dark]"
          />
        </div>

        {/* Proveedor */}
        <div className="flex flex-col">
          <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
            Proveedor
          </label>
          <input
            type="text"
            value={data.supplier}
            onChange={(e) => onChange({ supplier: e.target.value })}
            className="w-full h-10 px-3 text-sm font-semibold text-slate-100 bg-[#0f172a] border border-slate-800 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder-slate-700"
            placeholder="Ej: Proveedor Oficial"
          />
        </div>

        {/* Imagen */}
        <div className="flex flex-col">
          <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
            Imagen Lote
          </label>
          <div className="flex items-center gap-3">
            <div
              onClick={triggerFileInput}
              className="w-10 h-10 rounded-lg bg-[#0f172a] border border-slate-800 flex items-center justify-center overflow-hidden cursor-pointer hover:border-slate-700 transition-all shrink-0"
            >
              {imagePreview ? (
                <img src={imagePreview} alt="Lote" className="w-full h-full object-cover" />
              ) : (
                <ImageIcon className="w-5 h-5 text-slate-600" />
              )}
            </div>
            <button
              type="button"
              onClick={triggerFileInput}
              className={`flex-1 h-10 text-[10px] font-extrabold uppercase tracking-widest rounded-lg flex items-center justify-center gap-2 border transition-all cursor-pointer ${
                imagePreview
                  ? 'bg-blue-950/40 text-blue-400 border-blue-800/40 hover:bg-blue-900/40'
                  : 'bg-[#0f172a] text-slate-300 border-slate-800 hover:bg-slate-900 hover:border-slate-700'
              }`}
            >
              <Camera className="w-3.5 h-3.5" />
              <span>{imagePreview ? 'Cambiar' : 'Subir'}</span>
            </button>
            <input
              type="file"
              ref={fileInputRef}
              onChange={handleImageChange}
              accept="image/*"
              className="hidden"
            />
          </div>
        </div>
      </div>

      {/* Grid 2: Calculations Container (Matches dark styling and generous input spacing) */}
      <div className="bg-[#0b0f19]/80 p-4 rounded-xl border border-slate-800/60">
        <div className="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-4 items-end">
          
          {/* UDS/ENV */}
          <div className="col-span-1 flex flex-col">
            <label className="text-[10px] font-black text-slate-400 tracking-widest uppercase mb-2 select-none truncate">
              UDS/ENV
            </label>
            <NumericFormat
              thousandSeparator="."
              decimalSeparator=","
              decimalScale={0}
              allowNegative={false}
              isAllowed={(values) => {
                const { value } = values;
                return limitIntegerDigits(value, 6);
              }}
              value={data.unitsPerPackage ?? ''}
              onValueChange={(values) => {
                onChange({ unitsPerPackage: values.floatValue });
              }}
              className="h-10 text-center text-sm font-semibold text-slate-100 bg-[#0f172a] border border-slate-800 rounded-lg px-2 w-full focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder-slate-700"
              placeholder="1"
            />
          </div>

          {/* Nº ENV */}
          <div className="col-span-1 flex flex-col">
            <label className="text-[10px] font-black text-slate-400 tracking-widest uppercase mb-2 select-none truncate">
              Nº ENV
            </label>
            <NumericFormat
              thousandSeparator="."
              decimalSeparator=","
              decimalScale={0}
              allowNegative={false}
              isAllowed={(values) => {
                const { value } = values;
                return limitIntegerDigits(value, 6);
              }}
              value={data.numPackages ?? ''}
              onValueChange={(values) => {
                onChange({ numPackages: values.floatValue });
              }}
              className="h-10 text-center text-sm font-semibold text-slate-100 bg-[#0f172a] border border-slate-800 rounded-lg px-2 w-full focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder-slate-700"
              placeholder="1"
            />
          </div>

          {/* TARIFA € */}
          <div className="col-span-1 flex flex-col">
            <label className="text-[10px] font-black text-slate-400 tracking-widest uppercase mb-2 select-none truncate">
              TARIFA €
            </label>
            <NumericFormat
              thousandSeparator="."
              decimalSeparator=","
              decimalScale={2}
              fixedDecimalScale={true}
              allowNegative={false}
              isAllowed={(values) => {
                const { value } = values;
                if (!value) return true;
                const parts = value.split('.');
                return parts[0].length <= 6;
              }}
              value={data.tariff ?? ''}
              onValueChange={(values) => {
                onChange({ tariff: values.floatValue });
              }}
              className="h-10 text-right text-sm font-semibold text-slate-100 bg-[#0f172a] border border-slate-800 rounded-lg px-3 w-full focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder-slate-750"
              placeholder="0,00"
            />
          </div>

          {/* DESC % */}
          <div className="col-span-1 flex flex-col">
            <label className="text-[10px] font-black text-slate-400 tracking-widest uppercase mb-2 select-none truncate">
              DESC %
            </label>
            <NumericFormat
              thousandSeparator="."
              decimalSeparator=","
              decimalScale={0}
              allowNegative={false}
              isAllowed={(values) => {
                const { floatValue, value } = values;
                if (!value) return true;
                if (value.length > 2) return false;
                return floatValue === undefined || (floatValue >= 0 && floatValue <= 99);
              }}
              value={data.discount ?? ''}
              onValueChange={(values) => {
                onChange({ discount: values.floatValue });
              }}
              className="h-10 text-right text-sm font-semibold text-slate-100 bg-[#0f172a] border border-slate-800 rounded-lg px-3 w-full focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder-slate-700"
              placeholder="0"
            />
          </div>

          {/* IVA % */}
          <div className="col-span-1 flex flex-col">
            <label className="text-[10px] font-black text-slate-400 tracking-widest uppercase mb-2 select-none truncate">
              IVA %
            </label>
            <NumericFormat
              thousandSeparator="."
              decimalSeparator=","
              decimalScale={0}
              allowNegative={false}
              isAllowed={(values) => {
                const { floatValue, value } = values;
                if (!value) return true;
                if (value.length > 2) return false;
                return floatValue === undefined || (floatValue >= 0 && floatValue <= 99);
              }}
              value={data.iva ?? ''}
              onValueChange={(values) => {
                onChange({ iva: values.floatValue });
              }}
              className="h-10 text-right text-sm font-semibold text-slate-100 bg-[#0f172a] border border-slate-800 rounded-lg px-3 w-full focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder-slate-700"
              placeholder="0"
            />
          </div>

          {/* P/UD */}
          <div className="col-span-1 sm:col-span-2 lg:col-span-1 flex flex-col">
            <label className="text-[10px] font-black text-slate-400 tracking-widest uppercase mb-2 select-none truncate">
              P/UD
            </label>
            <div className="relative w-full">
              <input
                type="text"
                readOnly
                value={formatEuropean(pUd)}
                className="h-10 text-center text-sm font-extrabold text-blue-400 bg-blue-950/20 border border-blue-900/40 rounded-lg px-2 w-full select-none cursor-default focus:outline-none"
              />
              <span className="absolute right-2 top-2.5 text-[9px] font-bold text-blue-600 select-none">
                €
              </span>
            </div>
          </div>

          {/* TOTAL */}
          <div className="col-span-2 sm:col-span-2 lg:col-span-1 flex flex-col">
            <label className="text-[10px] font-black text-slate-400 tracking-widest uppercase mb-2 select-none truncate">
              TOTAL
            </label>
            <div className="relative w-full">
              <input
                type="text"
                readOnly
                value={formatEuropean(total)}
                className="h-10 text-right text-sm font-black text-blue-400 bg-blue-950/40 border border-blue-500/25 rounded-lg pl-2 pr-6 w-full select-none cursor-default focus:outline-none"
              />
              <span className="absolute right-2.5 top-2.5 text-[9px] font-bold text-blue-400 select-none">
                €
              </span>
            </div>
          </div>

        </div>
      </div>
    </div>
  );
};
