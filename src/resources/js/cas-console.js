import { EditorView, basicSetup } from "codemirror"
import { keymap } from "@codemirror/view"
import { indentWithTab } from "@codemirror/commands"
import { oneDark } from "@codemirror/theme-one-dark"
import { StreamLanguage } from "@codemirror/language"

// Simple Octave/MATLAB grammar for CodeMirror 6
const octaveLanguage = StreamLanguage.define({
    token(stream) {
        if (stream.eatSpace()) return null;

        // Comments
        if (stream.match(/[#%]/)) {
            stream.skipToEnd();
            return "comment";
        }

        // Strings
        if (stream.match(/['"]/)) {
            while (!stream.eol()) {
                if (stream.next() === stream.current()) break;
            }
            return "string";
        }

        // Numbers
        if (stream.match(/\d+(\.\d+)?(e[+-]?\d+)?/i)) {
            return "number";
        }

        // Keywords
        const keywords = /^(?:function|end|if|else|elseif|for|while|return|break|continue|switch|case|otherwise|try|catch|global|persistent|pkg|disp|plot|lsim|ss|lqr|inv|ones|size|jsonencode|struct)\b/;
        if (stream.match(keywords)) {
            return "keyword";
        }

        // Identifiers/Variables
        if (stream.match(/[a-zA-Z_][a-zA-Z0-9_]*/)) {
            return "variableName";
        }

        // Operators
        if (stream.match(/[+\-*\/\\^=<>!&|]/)) {
            return "operator";
        }

        stream.next();
        return null;
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const casForm = document.getElementById('cas-form');
    const editorContainer = document.getElementById('editor-container');
    if (!casForm || !editorContainer) return;

    const runBtn = document.getElementById('run-btn');
    const clearBtn = document.getElementById('clear-btn');
    const errorDiv = document.getElementById('error-message');
    const lastBatchDiv = document.getElementById('last-batch-output');

    // Samples data
    const samples = {
        ball: `% Ball & Beam quick sample\nr = 0.25\na = 1+1\na+2\nr*4`,
        pendulum: `% Inverted pendulum quick sample\nx = 0.2\ntheta = 0\nx + 0.3\ntheta + 1`
    };

    // Configuration
    const config = {
        sessionToken: casForm.dataset.sessionToken,
        apiKey: casForm.dataset.apiKey,
        labels: {
            run: runBtn.dataset.labelRun,
            running: runBtn.dataset.labelRunning
        }
    };

    // Initialize CodeMirror 6 with Octave support
    const editor = new EditorView({
        doc: samples.ball,
        extensions: [
            basicSetup,
            octaveLanguage,
            keymap.of([indentWithTab]),
            oneDark,
            EditorView.lineWrapping
        ],
        parent: editorContainer
    });

    // Global function replacement for sample loading
    window.loadCasSample = (type) => {
        if (samples[type]) {
            editor.dispatch({
                changes: { from: 0, to: editor.state.doc.length, insert: samples[type] }
            });
        }
    };

    // Clear button logic
    clearBtn.addEventListener('click', () => {
        editor.dispatch({
            changes: { from: 0, to: editor.state.doc.length, insert: "" }
        });
    });

    // Event listener for form submission
    casForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const script = editor.state.doc.toString();
        if (!script.trim()) return;
        
        runBtn.disabled = true;
        runBtn.innerText = config.labels.running;
        errorDiv.classList.add('hidden');
        
        try {
            const response = await fetch('/api/cas/execute', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'X-API-KEY': config.apiKey
                },
                body: JSON.stringify({
                    command: script,
                    session_token: config.sessionToken
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || data.error || 'Server error');
            }

            const row = document.createElement('div');
            row.className = "rounded border border-white/10 p-2 bg-white/5";
            row.innerHTML = `
                <p class="text-slate-400 whitespace-pre-wrap">&gt; ${escapeHtml(script)}</p>
                <p class="text-emerald-200 whitespace-pre-wrap">${escapeHtml(data.output)}</p>
            `;

            lastBatchDiv.replaceChildren(row);
            
        } catch (err) {
            errorDiv.innerText = err.message;
            errorDiv.classList.remove('hidden');
        } finally {
            runBtn.disabled = false;
            runBtn.innerText = config.labels.run;
        }
    });

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
