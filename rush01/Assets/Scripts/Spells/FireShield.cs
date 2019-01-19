using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class FireShield : MonoBehaviour {

	bool active;
	public float cd = 0.5f;
	public float damage = 10;
	float time;

	Transform child;
	float manaCost = 5;

	public void activeSpell()
	{
		active = !active;
	}
	void OnEnable()
	{
		child = transform.GetChild(0);
		time = Time.timeSinceLevelLoad;
	}


	public void addSpell(int c)
	{
		if (c == 1)
		{
			SpellsTab.instance.spell1.RemoveAllListeners();
			SpellsTab.instance.spell1.AddListener(activeSpell);
		}
		if (c == 2)
		{
			SpellsTab.instance.spell2.RemoveAllListeners();
			SpellsTab.instance.spell2.AddListener(activeSpell);	
		}
		if (c == 3)
		{
			SpellsTab.instance.spell3.RemoveAllListeners();
			SpellsTab.instance.spell3.AddListener(activeSpell);
		}
		if (c == 4)
		{
			SpellsTab.instance.spell4.RemoveAllListeners();
			SpellsTab.instance.spell4.AddListener(activeSpell);
		}
	}
	void Update () {

		if (!active)
		{
			child.gameObject.SetActive(false);
			return;
		}
		if (manaCost > MayaMove.instance.Mana)
			{
				active = false;
				return;
		}
			child.gameObject.SetActive(true);
		if (time <= Time.timeSinceLevelLoad)
		{

			MayaMove.instance.Mana -= manaCost;

			time = Time.timeSinceLevelLoad + cd;
			Collider[] hitColliders = Physics.OverlapSphere(transform.position, 2, 1<<9);
			int i = 0;
			while (i < hitColliders.Length)
			{
				hitColliders[i].GetComponent<ZombieMove>().TakeDmg(damage); //takeDamage
				i++;
			}
		}
	}
}
