//using System;
using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using System;

public class SpellUIManager : MonoBehaviour {

	public CanvasGroup SpellPicker;
	public SpellsTab Spelltab;
	private int slotId;
	private int spellId;

	private Button activeSlot;

	public Button Spell1;
	public Button Spell2;
	public Button Spell3;
	public Button Spell4;
	public Button Spell5;
	public Button Spell6;

	private int []tabId = new int []{ -1, -1, -1, -1};

	// Use this for initialization
	void Start () {
		SpellPicker.alpha = 0f;
	}
	
	// Update is called once per frame
	void Update () {
		if (SpellPicker.alpha == 1f){
			displaySpells();
		}
		if (Input.GetKey(KeyCode.Escape)){
			SpellPicker.alpha = 0f;
		}
	}

	void displaySpells(){
		if (Spelltab.FireballLvl > 0 && Array.IndexOf(tabId, 0) == -1){
			Spell1.interactable = true;
		}
		else {
			Spell1.interactable = false;
		}
		if (Spelltab.HealLvl > 0 && Array.IndexOf(tabId, 1) == -1){
			Spell2.interactable = true;
		}
		else {
			Spell2.interactable = false;
		}
		if (Spelltab.FirespikeLvl > 0 && Array.IndexOf(tabId, 2) == -1){
			Spell3.interactable = true;
		}
		else {
			Spell3.interactable = false;
		}
		if (Spelltab.FirewalkLvl > 0 && Array.IndexOf(tabId, 3) == -1){
			Spell4.interactable = true;
		}
		else {
			Spell4.interactable = false;
		}
		if (Spelltab.BurstballLvl > 0 && Array.IndexOf(tabId, 4) == -1){
			Spell5.interactable = true;
		}
		else {
			Spell5.interactable = false;
		}
		if (Spelltab.FirearmorLvl > 0 && Array.IndexOf(tabId, 5) == -1){
			Spell6.interactable = true;
		}
		else {
			Spell6.interactable = false;
		}
	}

	public void showSpellPicker(Button slot){
		SpellPicker.alpha = 1f;
		if (slot){
			slotId = int.Parse(slot.name);
			activeSlot = slot;
		}
	}

	public void pickaSpell(Button spell){
		SpellPicker.alpha = 0f;
		spellId = int.Parse(spell.name);
		if (activeSlot == null || activeSlot.GetComponentInChildren<RawImage>() == null)
			return;
		activeSlot.GetComponentInChildren<RawImage>().texture = spell.GetComponentInChildren<RawImage>().texture;

		if (slotId == 0)
			SpellsTab.instance.spell1.RemoveAllListeners();
		if (slotId == 1)
			SpellsTab.instance.spell2.RemoveAllListeners();
		if (slotId == 2)
			SpellsTab.instance.spell3.RemoveAllListeners();
		if (slotId == 3)
			SpellsTab.instance.spell4.RemoveAllListeners();

		if (tabId[slotId] == 3)
		{
			MayaMove.instance.agent.speed = 5;
		}
		if (tabId[slotId] == 4)
		{
			SpellsTab.instance.fireball.boostedFireball = false;
			SpellsTab.instance.boostedFireball = false;
			SpellsTab.instance.fireball.damage = SpellsTab.instance.FireballLvl * 10f;
		}

		tabId[slotId] = spellId;

		if (spellId == 3)
		{
			MayaMove.instance.agent.speed = 5 + SpellsTab.instance.FirewalkLvl * 2;
		}
		if (spellId == 4)
		{
			SpellsTab.instance.fireball.boostedFireball = true;
			SpellsTab.instance.boostedFireball = true;
			SpellsTab.instance.fireball.damage = SpellsTab.instance.FireballLvl * 10f + SpellsTab.instance.BurstballLvl * 15f;
		}

		if (spellId == 0)
			SpellsTab.instance.fireball.addSpell(slotId+1);
		if (spellId == 1)
			SpellsTab.instance.healSpell.addSpell(slotId+1);
		if (spellId == 2)
			SpellsTab.instance.aoeSpell.addSpell(slotId+1);
		if (spellId == 5)
			SpellsTab.instance.fireShield.addSpell(slotId+1);
	}

}
