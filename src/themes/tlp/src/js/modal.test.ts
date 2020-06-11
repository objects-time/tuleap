/*
 * Copyright (c) Enalean, 2020-Present. All Rights Reserved.
 *
 * This file is a part of Tuleap.
 *
 * Tuleap is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Tuleap is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tuleap. If not, see <http://www.gnu.org/licenses/>.
 */

import { Modal, modal as createModal } from "./modal";

const EVENT_TLP_MODAL_SHOWN = "tlp-modal-shown";
const EVENT_TLP_MODAL_HIDDEN = "tlp-modal-hidden";

jest.useFakeTimers();

describe(`Modal`, () => {
    let modal_element: HTMLElement;

    beforeEach(() => {
        modal_element = document.createElement("div");
        document.body.append(modal_element);
    });

    afterEach(() => {
        modal_element.remove();
    });

    describe(`show()`, () => {
        let modal: Modal;
        beforeEach(() => {
            modal = createModal(modal_element);
        });
        afterEach(() => {
            modal.destroy();
        });

        it(`will add the "display" and "shown" CSS classes to the modal element`, () => {
            modal.show();
            expect(modal_element.classList.contains("tlp-modal-display")).toBe(true);
            expectTheModalToBeShown(modal_element);
        });

        it(`will dispatch the "shown" event`, () => {
            let event_dispatched = false;
            modal.addEventListener(EVENT_TLP_MODAL_SHOWN, () => {
                event_dispatched = true;
            });
            modal.show();
            expect(event_dispatched).toBe(true);
        });

        it(`will create and show a backdrop element`, () => {
            modal.show();
            const backdrop = document.querySelector("div#tlp-modal-backdrop");
            expect(backdrop).not.toBeNull();
            if (backdrop === null) {
                throw new Error("backdrop should exist in the document");
            }
            expect(backdrop.classList.contains("tlp-modal-backdrop-shown")).toBe(true);
        });
    });

    describe(`hide()`, () => {
        let modal: Modal;

        describe(`for a regular modal`, () => {
            beforeEach(() => {
                modal = createModal(modal_element);
            });
            afterEach(() => {
                modal.destroy();
            });

            it(`will remove the "shown" CSS class from the modal element`, () => {
                modal.show();
                modal.hide();
                expectTheModalToBeHidden(modal_element);
            });

            it(`will remove the "backdrop shown" CSS class from the backdrop element`, () => {
                modal.show();
                const backdrop = document.querySelector("#tlp-modal-backdrop");
                if (backdrop === null) {
                    throw new Error("backdrop should exist in the document");
                }
                modal.hide();
                expect(backdrop.classList.contains("tlp-modal-backdrop-shown")).toBe(false);
            });

            it(`will remove the backdrop element after a delay`, () => {
                modal.show();
                modal.hide();
                jest.runAllTimers();

                const backdrop = document.querySelector("#tlp-modal-backdrop");
                expect(backdrop).toBeNull();
            });

            it(`will remove the "display" CSS class after a delay`, () => {
                modal.show();
                modal.hide();
                jest.runAllTimers();

                expect(modal_element.classList.contains("tlp-modal-display")).toBe(false);
            });

            it(`will dispatch the "hidden" event after a delay`, () => {
                modal.show();
                let event_dispatched = false;
                modal.addEventListener(EVENT_TLP_MODAL_HIDDEN, () => {
                    event_dispatched = true;
                });

                modal.hide();
                jest.runAllTimers();
                expect(event_dispatched).toBe(true);
            });

            it(`when I hide a modal that was never "shown" first, it will not crash`, () => {
                expect(() => {
                    modal.hide();
                    jest.runAllTimers();
                }).not.toThrow();
            });
        });

        it(`given the modal had the "destroy_on_hide" option, it will destroy the modal`, () => {
            const first_closing_element = document.createElement("span");
            first_closing_element.dataset.dismiss = "modal";
            const removeFirstClickListener = jest.spyOn(
                first_closing_element,
                "removeEventListener"
            );
            const second_closing_element = document.createElement("span");
            second_closing_element.dataset.dismiss = "modal";
            const removeSecondClickListener = jest.spyOn(
                second_closing_element,
                "removeEventListener"
            );
            modal_element.append(first_closing_element, second_closing_element);
            modal = createModal(modal_element, { destroy_on_hide: true });
            modal.show();
            modal.hide();

            expect(removeFirstClickListener).toHaveBeenCalled();
            expect(removeSecondClickListener).toHaveBeenCalled();
        });
    });

    describe(`toggle()`, () => {
        let modal: Modal;
        beforeEach(() => {
            modal = createModal(modal_element);
        });
        afterEach(() => {
            modal.destroy();
        });

        it(`when the modal is hidden, it will show it`, () => {
            modal.toggle();

            expectTheModalToBeShown(modal_element);

            modal.hide();
        });

        it(`when the modal is shown, it will hide it`, () => {
            modal.show();
            modal.toggle();

            expectTheModalToBeHidden(modal_element);
        });
    });

    it(`when I click on the backdrop element, it will hide the modal`, () => {
        const modal = createModal(modal_element);
        modal.show();
        const backdrop = document.querySelector("#tlp-modal-backdrop");
        if (backdrop === null || !(backdrop instanceof HTMLElement)) {
            throw new Error("backdrop should exist in the document");
        }

        backdrop.dispatchEvent(new MouseEvent("click"));
        expectTheModalToBeHidden(modal_element);

        modal.destroy();
    });

    it(`when I click on a [data-dismiss=modal] element, it will hide the modal`, () => {
        const closing_element = document.createElement("span");
        closing_element.dataset.dismiss = "modal";
        modal_element.append(closing_element);
        const modal = createModal(modal_element);
        modal.show();

        closing_element.dispatchEvent(new MouseEvent("click"));
        expectTheModalToBeHidden(modal_element);

        modal.destroy();
    });

    describe(`removeEventListener`, () => {
        it(`removes a listener from the modal`, () => {
            const modal = createModal(modal_element);
            const listener = jest.fn();
            modal.addEventListener(EVENT_TLP_MODAL_HIDDEN, listener);
            modal.show();

            modal.removeEventListener(EVENT_TLP_MODAL_HIDDEN, listener);
            modal.hide();
            expect(listener).not.toHaveBeenCalled();

            modal.destroy();
        });
    });

    describe(`when the modal has the keyboard option`, () => {
        let modal: Modal;
        beforeEach(() => {
            modal = createModal(modal_element, { keyboard: true });
            modal.show();
        });
        afterEach(() => {
            modal.destroy();
        });

        it(`and I hit a key that isn't Escape, nothing happens`, () => {
            document.body.dispatchEvent(new KeyboardEvent("keyup", { key: "A", bubbles: true }));

            expectTheModalToBeShown(modal_element);
        });

        it(`and I hit the Escape key inside an input element, nothing happens`, () => {
            const input = document.createElement("input");
            document.body.append(input);
            simulateEscapeKey(input);

            expectTheModalToBeShown(modal_element);

            input.remove();
        });

        it(`and I hit the Escape key inside a select element, nothing happens`, () => {
            const select = document.createElement("select");
            document.body.append(select);
            simulateEscapeKey(select);

            expectTheModalToBeShown(modal_element);

            select.remove();
        });

        it(`and I hit the Escape key inside a textarea element, nothing happens`, () => {
            const textarea = document.createElement("textarea");
            document.body.append(textarea);
            simulateEscapeKey(textarea);

            expectTheModalToBeShown(modal_element);

            textarea.remove();
        });

        it(`and given the modal was hidden, when I hit the Escape key, nothing happens`, () => {
            modal.hide();
            simulateEscapeKey(document.body);

            expectTheModalToBeHidden(modal_element);
        });

        it(`and I hit the Escape key, the modal will be hidden`, () => {
            simulateEscapeKey(document.body);

            expectTheModalToBeHidden(modal_element);
        });

        it(`when it is destroyed, the modal will remove its keyup listener`, () => {
            const removeEventListener = jest.spyOn(document, "removeEventListener");
            modal.destroy();

            expect(removeEventListener).toHaveBeenCalledWith("keyup", expect.anything());
        });
    });
});

function expectTheModalToBeShown(modal_element: HTMLElement): void {
    expect(modal_element.classList.contains("tlp-modal-shown")).toBe(true);
}

function expectTheModalToBeHidden(modal_element: HTMLElement): void {
    expect(modal_element.classList.contains("tlp-modal-shown")).toBe(false);
}

function simulateEscapeKey(element: HTMLElement): void {
    element.dispatchEvent(new KeyboardEvent("keyup", { key: "Escape", bubbles: true }));
}