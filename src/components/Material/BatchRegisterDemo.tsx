import React, { useState } from 'react';
import { BatchRegisterRow, BatchRowData } from './BatchRegisterRow';
import { Plus, BarChart3, AlertCircle, ShoppingBag, Euro, ChevronLeft, Save, X, HardDrive, RefreshCw } from 'lucide-react';

export const BatchRegisterDemo: React.FC = () => {
  const [activeTab, setActiveTab] = useState<'datos' | 'lotes'>('lotes');
  const [batches, setBatches] = useState<BatchRowData[]>([
    {
      id: '1',
      batchNumber: 'LOTE-A205',
      expirationDate: '2028-12-31',
      supplier: 'Distribuciones Médicas del Sur',
      unitsPerPackage: 15000,
      numPackages: 250,
      tariff: 45.50,
      discount: 10,
      iva: 21,
    },
  ]);

  // Add row
  const handleAddBatch = () => {
    const newId = (batches.length > 0 ? Math.max(...batches.map(b => parseInt(b.id) || 0)) + 1 : 1).toString();
    const newBatch: BatchRowData = {
      id: newId,
      batchNumber: '',
      expirationDate: '',
      supplier: '',
      unitsPerPackage: 1,
      numPackages: 1,
      tariff: undefined,
      discount: 0,
      iva: 21,
    };
    setBatches([...batches, newBatch]);
  };

  // Delete row
  const handleDeleteBatch = (id: string) => {
    if (batches.length === 1) {
      setBatches([
        {
          id: '1',
          batchNumber: '',
          expirationDate: '',
          supplier: '',
          unitsPerPackage: 1,
          numPackages: 1,
          tariff: undefined,
          discount: 0,
          iva: 21,
        },
      ]);
      return;
    }
    setBatches(batches.filter((b) => b.id !== id));
  };

  // Update field
  const handleUpdateBatch = (id: string, updatedFields: Partial<BatchRowData>) => {
    setBatches(
      batches.map((b) => {
        if (b.id === id) {
          return { ...b, ...updatedFields };
        }
        return b;
      })
    );
  };

  // Summaries
  const totalPackages = batches.reduce((sum, b) => sum + (b.numPackages ?? 0), 0);
  const totalStock = batches.reduce((sum, b) => {
    const u = b.unitsPerPackage ?? 0;
    const n = b.numPackages ?? 0;
    return sum + u * n;
  }, 0);

  const totalCost = batches.reduce((sum, b) => {
    const tariff = b.tariff ?? 0;
    const discount = b.discount ?? 0;
    const iva = b.iva ?? 0;
    const n = b.numPackages ?? 0;

    const discountMultiplier = 1 - discount / 100;
    const ivaMultiplier = 1 + iva / 100;
    const priceWithTaxAndDiscount = tariff * discountMultiplier * ivaMultiplier;
    return sum + priceWithTaxAndDiscount * n;
  }, 0);

  const formatEuropean = (value: number) => {
    return new Intl.NumberFormat('es-ES', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }).format(value);
  };

  const formatStock = (value: number) => {
    return new Intl.NumberFormat('es-ES', {
      maximumFractionDigits: 0,
    }).format(value);
  };

  return (
    <div className="min-h-screen bg-[#0b0f19] text-slate-100 p-4 sm:p-6 md:p-8 font-sans selection:bg-blue-500 selection:text-white">
      <div className="max-w-[1700px] mx-auto">
        
        {/* Top Header bar with buttons matching exact layout */}
        <header className="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-900 pb-5">
          <div className="flex items-center gap-3">
            <div className="w-6 h-6 rounded-full border border-blue-500 flex items-center justify-center text-blue-500 hover:bg-blue-950 transition-all cursor-pointer">
              <Plus className="w-3.5 h-3.5" />
            </div>
            <h1 className="text-lg font-extrabold text-white tracking-wider uppercase">
              CREAR NUEVO MATERIAL
            </h1>
          </div>

          {/* Action buttons (solid blue, capsules) */}
          <div className="flex items-center gap-3 self-start md:self-auto">
            <button
              type="button"
              className="h-10 px-4 rounded-lg bg-slate-900 border border-slate-800 text-slate-400 hover:text-slate-200 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 transition-all cursor-pointer hover:bg-slate-850"
            >
              <X className="w-3.5 h-3.5" />
              <span>Cancelar</span>
            </button>
            <button
              type="button"
              onClick={() => alert('¡Material Guardado!\n' + JSON.stringify(batches))}
              className="h-10 px-5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-[11px] font-black uppercase tracking-wider flex items-center gap-2 transition-all cursor-pointer shadow-lg active:scale-98"
            >
              <Save className="w-3.5 h-3.5" />
              <span>GUARDAR MATERIAL</span>
            </button>
          </div>
        </header>

        {/* Tab navigation matching the exact capsule styles */}
        <div className="flex items-center gap-6 border-b border-slate-800 pb-3 mb-6">
          <button
            type="button"
            onClick={() => setActiveTab('datos')}
            className={`text-xs font-bold uppercase tracking-widest pb-2.5 transition-all border-b-2 cursor-pointer ${
              activeTab === 'datos'
                ? 'text-blue-500 border-blue-500 font-extrabold'
                : 'text-slate-500 hover:text-slate-300 border-transparent'
            }`}
          >
            DATOS GENERALES
          </button>
          <button
            type="button"
            onClick={() => setActiveTab('lotes')}
            className={`text-xs font-bold uppercase tracking-widest pb-2.5 transition-all border-b-2 cursor-pointer ${
              activeTab === 'lotes'
                ? 'text-blue-500 border-blue-500 font-extrabold'
                : 'text-slate-500 hover:text-slate-300 border-transparent'
            }`}
          >
            LOTES Y STOCK
          </button>
        </div>

        {/* Main Layout Grid */}
        <div className="flex flex-col lg:flex-row gap-6 items-start">
          
          {/* Main Panel Column */}
          <main className="flex-1 w-full space-y-6">
            
            {/* Fieldset layout wrapper with orange/blue/green headers */}
            <div className="bg-[#111827] border border-slate-800 rounded-xl p-5 sm:p-6 shadow-2xl relative">
              <div className="flex items-center gap-2.5 border-b border-slate-800 pb-4 mb-6">
                <div className="w-2.5 h-2.5 rounded bg-blue-500 animate-pulse" />
                <h3 className="text-xs font-extrabold text-blue-400 uppercase tracking-widest">
                  Registro de Lotes Sanitarios
                </h3>
              </div>

              {/* Dynamic rows mapping */}
              <div className="space-y-4">
                {batches.map((batch, index) => (
                  <BatchRegisterRow
                    key={batch.id}
                    index={index + 1}
                    data={batch}
                    onChange={(updatedFields) => handleUpdateBatch(batch.id, updatedFields)}
                    onDelete={() => handleDeleteBatch(batch.id)}
                  />
                ))}
              </div>

              {/* Add Lote button */}
              <div className="flex justify-end pt-4">
                <button
                  type="button"
                  onClick={handleAddBatch}
                  className="h-10 px-5 rounded-lg bg-[#0f172a] hover:bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-300 text-[10px] font-bold uppercase tracking-wider flex items-center gap-2 transition-all cursor-pointer"
                >
                  <Plus className="w-3.5 h-3.5 text-blue-500" />
                  <span>Añadir Registro Lote</span>
                </button>
              </div>
            </div>
          </main>

          {/* Economic Sidebar matching the screenshot styling */}
          <aside className="w-full lg:width-[350px] lg:w-[350px] shrink-0 space-y-6 lg:sticky lg:top-8">
            
            {/* Total Stock Summary Box */}
            <div className="relative bg-[#111827] border border-slate-800 rounded-xl p-6 shadow-2xl overflow-hidden group">
              <div className="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl" />
              <div className="flex items-center justify-between mb-4 border-b border-slate-800/60 pb-3">
                <span className="text-[10px] font-bold text-blue-400 uppercase tracking-widest">
                  Stock Total
                </span>
                <BarChart3 className="w-4 h-4 text-blue-500" />
              </div>
              <div className="flex items-baseline gap-1">
                <span className="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight break-all">
                  {formatStock(totalStock)}
                </span>
                <span className="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1.5">
                  Unidades
                </span>
              </div>
            </div>

            {/* Economic Summary panel */}
            <div className="bg-[#111827] border border-slate-800 rounded-xl p-6 shadow-2xl relative">
              <div className="flex items-center gap-2.5 border-b border-slate-800 pb-3 mb-5">
                <ShoppingBag className="w-4 h-4 text-blue-500" />
                <h3 className="text-[10px] font-bold text-blue-400 uppercase tracking-widest">
                  Resumen Económico
                </h3>
              </div>

              <div className="space-y-5">
                {/* Total Packages */}
                <div className="flex flex-col">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                    Total Envases
                  </label>
                  <div className="relative">
                    <input
                      type="text"
                      readOnly
                      value={formatStock(totalPackages)}
                      className="w-full h-11 px-4 text-sm font-extrabold text-slate-300 bg-[#0f172a] border border-slate-800 rounded-lg text-center cursor-default focus:outline-none"
                    />
                    <span className="absolute right-3.5 top-3 text-[9px] font-bold text-slate-600 uppercase">
                      Envases
                    </span>
                  </div>
                </div>

                {/* Investment Estimated */}
                <div className="flex flex-col">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                    Inversión Estimada
                  </label>
                  <div className="relative flex items-center bg-[#0f172a] rounded-lg border border-slate-800 p-2.5">
                    <Euro className="w-4 h-4 text-blue-500 shrink-0 ml-1" />
                    <input
                      type="text"
                      readOnly
                      value={formatEuropean(totalCost)}
                      className="w-full bg-transparent border-none text-right text-xl font-black text-blue-400 outline-none select-none cursor-default pr-2"
                    />
                    <span className="text-[10px] font-bold text-blue-500 select-none mr-1">
                      EUR
                    </span>
                  </div>
                </div>
              </div>
            </div>

            {/* Sidebar quick actions */}
            <div className="bg-[#111827] border border-slate-800 rounded-xl p-5 shadow-2xl flex flex-col gap-3">
              <button
                type="button"
                onClick={() => alert('¡Guardado!')}
                className="w-full h-11 bg-blue-600 hover:bg-blue-500 text-white text-xs font-black uppercase tracking-wider rounded-lg transition-all cursor-pointer shadow-md shadow-blue-600/10 active:scale-98"
              >
                Guardar Formulario
              </button>
            </div>

          </aside>

        </div>

      </div>
    </div>
  );
};
export default BatchRegisterDemo;
