import './bootstrap.js'
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css'

// for nebula on homescreen
import { createNebula } from '@flodlc/nebula';

const element = document.getElementById("nebula-element");

const nebula = createNebula(element, {
    starsCount: 490,
    startsColor: "#FFFFFF",
    starsRotationSpeed: 3,
    cometFrequence: 100,
    nebulasIntensity: 11,
    bgColor: "rgb(8,8,8)",
    sunScale: 0,
    planetsScale: 0,
    solarSystemOrbite: 0,
    solarSystemSpeedOrbit: 0
});
